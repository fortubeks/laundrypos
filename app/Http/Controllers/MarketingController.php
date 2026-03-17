<?php
namespace App\Http\Controllers;

use App\Helpers\ApiHelper;
use App\Jobs\SendMarketingCampaignJob;
use App\Models\Customer;
use Illuminate\Http\Request;
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

        $user                   = $request->user();
        $laundryId              = $user->laundry_id;
        $businessName           = $user->user_account?->app_settings?->business_name ?? $user->name ?? 'Your Laundry';
        $businessWhatsAppNumber = businesswhatsappnumber($user);
        $templateId             = $request->input('template_id');
        $content                = $request->input('content');
        $customerIds            = $request->input('customer_ids');
        $channel                = $request->input('channel');

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

            SendMarketingCampaignJob::dispatch(
                $customer,
                $content,
                $businessName,
                $channel,
                $templateId,
                $businessWhatsAppNumber
            );
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
    // private function sendWhatsApp(Customer $customer, array $content, string $businessName, $user): bool
    // {
    //     $to = $customer->whatsappNumber($user);

    //     if (! $to) {
    //         return false;
    //     }

    //     $title      = $content['title'] ?? '';
    //     $message    = $content['message'] ?? '';
    //     $buttonText = $content['buttonText'] ?? '';

    //     try {

    //         $client = new Client([
    //             'base_uri' => 'https://graph.facebook.com/v17.0/',
    //         ]);

    //         $client->post('108752848993090/messages', [
    //             'headers' => [
    //                 'Authorization' => 'Bearer ' . env('WHATSAPP_TOKEN'),
    //                 'Content-Type'  => 'application/json',
    //             ],
    //             'json'    => [
    //                 'messaging_product' => 'whatsapp',
    //                 'to'                => $to,
    //                 'type'              => 'template',
    //                 'template'          => [
    //                     'name'       => 'marketing_message',
    //                     'language'   => [
    //                         'code' => 'en',
    //                     ],
    //                     'components' => [
    //                         [
    //                             'type'       => 'body',
    //                             'parameters' => [
    //                                 [
    //                                     'type' => 'text',
    //                                     'text' => $businessName,
    //                                 ],
    //                                 [
    //                                     'type' => 'text',
    //                                     'text' => $title,
    //                                 ],
    //                                 [
    //                                     'type' => 'text',
    //                                     'text' => $message,
    //                                 ],
    //                                 [
    //                                     'type' => 'text',
    //                                     'text' => $buttonText,
    //                                 ],
    //                             ],
    //                         ],
    //                     ],
    //                 ],
    //             ],
    //         ]);

    //         return true;

    //     } catch (RequestException $e) {

    //         Log::error('Marketing WhatsApp failed for customer ' . $customer->id, [
    //             'error'    => $e->getMessage(),
    //             'response' => optional($e->getResponse())->getBody()->getContents(),
    //         ]);

    //         return false;
    //     }
    // }
}
