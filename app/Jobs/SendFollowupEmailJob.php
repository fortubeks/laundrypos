<?php
namespace App\Jobs;

use App\Mail\FollowupEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendFollowupEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $attemptNumber;

    public function __construct(User $user, $attemptNumber)
    {
        $this->user          = $user;
        $this->attemptNumber = $attemptNumber;
    }

    public function handle()
    {
        Log::info('Sending follow-up email #' . $this->attemptNumber . ' to user ID ' . $this->user->id);
        Mail::to($this->user->email)->send(
            new FollowupEmail($this->user->name, $this->attemptNumber)
        );
    }
}
