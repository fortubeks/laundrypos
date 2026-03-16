<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Mail\MarketingEmail;
use App\Models\Customer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class MarketingController extends Controller
{
    private const ALLOWED_TEMPLATES = [
        'welcome_back',
        'special_offer',
        'seasonal',
        'order_ready',
        'loyalty',
        'custom',
    ];

    /**
     * Send a marketing campaign (email, WhatsApp, or both) to one or more
     * customers belonging to the authenticated user's laundry.
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id'     => 'required|string|in:' . implode(',', self::ALLOWED_TEMPLATES),
            'content'         => 'required|array',
            'content.subject' => 'nullable|string|max:255',
            'content.message' => 'required|string',
            'customer_ids'    => 'required|array|min:1',
            'customer_ids.*'  => 'required|integer',
            'channel'         => 'required|string|in:email,whatsapp,both',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all(), 'code' => 422], 422);
        }

        $user         = $request->user();
        $laundryId    = $user->laundry_id;
        $businessName = $user->user_account?->app_settings?->business_name ?? $user->name ?? 'Your Laundry';
        $templateId   = $request->input('template_id');
        $content      = $request->input('content');
        $customerIds  = $request->input('customer_ids');
        $channel      = $request->input('channel');

        /* Fetch all requested customers belonging to this laundry */
        $customers = Customer::whereIn('id', $customerIds)
            ->where('laundry_id', $laundryId)
            ->with('country')
            ->get();

        if ($customers->isEmpty()) {
            return ApiHelper::problemResponse('No valid customers found for the selected IDs.', 422);
        }

        $emailSent = $emailFailed = $waSent = $waFailed = 0;

        foreach ($customers as $customer) {
            $customerName = trim(
                ($customer->title ? $customer->title . ' ' : '') .
                $customer->first_name .
                ($customer->last_name ? ' ' . $customer->last_name : '')
            );

            /* ── Email ─────────────────────────────────────────────── */
            if (in_array($channel, ['email', 'both']) && $customer->email) {
                try {
                    Mail::to($customer->email)->send(
                        new MarketingEmail($templateId, $content, $customerName, $businessName)
                    );
                    $emailSent++;
                } catch (\Exception $e) {
                    Log::error('Marketing email failed for customer ' . $customer->id . ': ' . $e->getMessage());
                    $emailFailed++;
                }
            }

            /* ── WhatsApp ───────────────────────────────────────────── */
            if (in_array($channel, ['whatsapp', 'both']) && $customer->phone) {
                if ($this->sendWhatsApp($customer, $content, $businessName, $user)) {
                    $waSent++;
                } else {
                    $waFailed++;
                }
            }
        }

        $parts = [];
        if (in_array($channel, ['email', 'both'])) {
            $parts[] = "Email — sent: {$emailSent}" . ($emailFailed ? ", failed: {$emailFailed}" : '');
        }
        if (in_array($channel, ['whatsapp', 'both'])) {
            $parts[] = "WhatsApp — sent: {$waSent}" . ($waFailed ? ", failed: {$waFailed}" : '');
        }

        return ApiHelper::validResponse(
            'Campaign complete. ' . implode('. ', $parts) . '.',
            [
                'email_sent'   => $emailSent,
                'email_failed' => $emailFailed,
                'wa_sent'      => $waSent,
                'wa_failed'    => $waFailed,
            ]
        );
    }

    /**
     * Send a WhatsApp text message to a single customer.
     * Returns true on success, false on failure.
     */
    private function sendWhatsApp(Customer $customer, array $content, string $businessName, $user): bool
    {
        $to = $customer->whatsappNumber($user);
        if (! $to) {
            return false;
        }

        $lines = array_filter([
            '*' . $businessName . '*',
            ! empty($content['title']) ? '*' . $content['title'] . '*' : null,
            $content['message'] ?? null,
            ! empty($content['buttonText']) ? '— ' . $content['buttonText'] : null,
        ]);

        $body = implode("\n\n", $lines);

        try {
            $client = new Client();
            $client->request('POST', 'https://graph.facebook.com/v17.0/108752848993090/messages', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('WHATSAPP_TOKEN'),
                    'Content-Type'  => 'application/json',
                ],
                'json'    => [
                    'messaging_product' => 'whatsapp',
                    'recipient_type'    => 'individual',
                    'to'                => $to,
                    'type'              => 'text',
                    'text'              => ['preview_url' => false, 'body' => $body],
                ],
            ]);
            return true;
        } catch (RequestException $e) {
            Log::error('Marketing WhatsApp failed for customer ' . $customer->id . ': ' . $e->getMessage());
            return false;
        }
    }
}
