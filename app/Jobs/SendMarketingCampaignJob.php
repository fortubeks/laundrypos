<?php
namespace App\Jobs;

use App\Mail\MarketingEmail;
use App\Models\Customer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMarketingCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $customer;
    protected $content;
    protected $businessName;
    protected $businessWhatsAppNumber;
    protected $channel;
    protected $templateId;

    public function __construct(
        Customer $customer,
        array $content,
        string $businessName,
        string $channel,
        string $templateId,
        ?string $businessWhatsAppNumber = null
    ) {
        $this->customer               = $customer;
        $this->content                = $content;
        $this->businessName           = $businessName;
        $this->businessWhatsAppNumber = $businessWhatsAppNumber;
        $this->channel                = $channel;
        $this->templateId             = $templateId;
    }

    public function handle()
    {
        $customer = $this->customer;

        $customerName = trim(
            ($customer->title ? $customer->title . ' ' : '') .
            $customer->first_name .
            ($customer->last_name ? ' ' . $customer->last_name : '')
        );

        /* Email */
        if (in_array($this->channel, ['email', 'both']) && $customer->email) {
            try {
                Mail::to($customer->email)->send(
                    new MarketingEmail($this->templateId, $this->content, $customerName, $this->businessName)
                );
            } catch (\Exception $e) {
                Log::error('Marketing email failed for customer ' . $customer->id . ': ' . $e->getMessage());
            }
        }

        /* WhatsApp */
        if (in_array($this->channel, ['whatsapp', 'both']) && $customer->phone) {

            $to = $customer->whatsappNumber();
            if (! $to) {
                return;
            }

            $title   = $this->content['title'] ?? '';
            $message = $this->content['message'] ?? '';
            $url     = 'https://wa.me/' . $this->businessWhatsAppNumber . '?text=' . urlencode($message);

            try {

                if (! $url) {
                    Log::warning('Marketing WhatsApp skipped: business WhatsApp number not set.', [
                        'customer_id' => $customer->id,
                    ]);

                    return;
                }

                Log::info('Sending WhatsApp', [
                    'to'         => $to,
                    'token'      => substr(env('WHATSAPP_TOKEN'), 0, 10) . '...', // hide rest
                    'template'   => 'marketing_1',
                    'parameters' => [$this->businessName, $title, $message, $url],
                ]);

                $client = new Client([
                    'base_uri' => 'https://graph.facebook.com/v22.0/',
                ]);

                $client->post('104443719426225/messages', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('WHATSAPP_TOKEN'),
                        'Content-Type'  => 'application/json',
                    ],
                    'json'    => [
                        'messaging_product' => 'whatsapp',
                        'to'                => $to,
                        'type'              => 'template',
                        'template'          => [
                            'name'       => 'marketing_1',
                            'language'   => [
                                'code' => 'en',
                            ],
                            'components' => [
                                [
                                    'type'       => 'body',
                                    'parameters' => [
                                        ['type' => 'text', 'text' => $this->businessName],
                                        ['type' => 'text', 'text' => $title],
                                        ['type' => 'text', 'text' => $message],
                                        ['type' => 'text', 'text' => $url],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]);

            } catch (RequestException $e) {
                Log::info('Attempting to send marketing WhatsApp message to customer ' . $customer->id . ' at number: ' . $to);
                Log::error('Marketing WhatsApp failed for customer ' . $customer->id, [
                    'error'    => $e->getMessage(),
                    'response' => optional($e->getResponse())->getBody()->getContents(),
                ]);
            }
        }
    }
}
