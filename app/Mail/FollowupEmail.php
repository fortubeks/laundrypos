<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FollowupEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $attemptNumber;

    public function __construct($name, $attemptNumber)
    {
        $this->name = $name;
        $this->attemptNumber = $attemptNumber;
    }

    public function build()
    {
        if ($this->attemptNumber == 1) {
            return $this->subject('Welcome to MyLaundryPOS, Let’s Get You Started')
                ->view('emails.followup_email_1')
                ->with([
                    'name' => $this->name,
                ]);
        } elseif ($this->attemptNumber == 2) {
            return $this->subject('Transform Your Laundry Operations in Minutes')
                ->view('emails.followup_email_2')
                ->with([
                    'name' => $this->name,
                ]);
        } elseif ($this->attemptNumber == 3) {
            return $this->subject('Manage Your Laundry Business From Anywhere')
                ->view('emails.followup_email_3')
                ->with([
                    'name' => $this->name,
                ]);
        }
    }
}
