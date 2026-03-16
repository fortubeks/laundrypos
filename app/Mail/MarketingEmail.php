<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $templateId;
    public array $content;
    public string $customerName;
    public string $businessName;

    public function __construct(
        string $templateId,
        array $content,
        string $customerName,
        string $businessName
    ) {
        $this->templateId   = $templateId;
        $this->content      = $content;
        $this->customerName = $customerName;
        $this->businessName = $businessName;
    }

    public function build(): self
    {
        $subject = $this->content['subject'] ?? 'A message from ' . $this->businessName;
        $viewMap = [
            'welcome_back'  => 'emails.marketing.welcome_back',
            'special_offer' => 'emails.marketing.special_offer',
            'seasonal'      => 'emails.marketing.seasonal',
            'order_ready'   => 'emails.marketing.order_ready',
            'loyalty'       => 'emails.marketing.loyalty',
            'custom'        => 'emails.marketing.custom',
        ];

        $view = $viewMap[$this->templateId] ?? 'emails.marketing.welcome_back';

        return $this->subject($subject)
            ->view($view)
            ->with([
                'content'      => $this->content,
                'customerName' => $this->customerName,
                'businessName' => $this->businessName,
            ]);
    }
}
