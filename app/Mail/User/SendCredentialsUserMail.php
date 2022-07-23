<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendCredentialsUserMail extends Mailable
{
    use Queueable, SerializesModels;
    public  $user;
    public  $clear_password;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user,string $clear_password)
    {
        $this->user = $user;
        $this->clear_password = $clear_password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('mail/sendcredentialuser.email.subject'))->markdown('emails.sendCredentialsUser');
    }
}
