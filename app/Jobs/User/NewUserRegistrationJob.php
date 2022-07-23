<?php

namespace App\Jobs\User;

use App\Mail\User\SendCredentialsUserMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class NewUserRegistrationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $userId;
    private $clearPassword;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $userId,string $clearPassword)
    {
        $this->userId = $userId;
        $this->clearPassword = $clearPassword;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->userId);
        if (!empty($user)) {
            Mail::to($user->email)->send(new SendCredentialsUserMail($user, $this->clearPassword));
        }
    }
}
