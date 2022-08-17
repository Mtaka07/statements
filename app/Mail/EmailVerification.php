<?php

namespace App\Mail;

use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailVerification)
    {
        $this->emailVerification = $emailVerification;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info(env('MAIL_FROM_ADDRESS'));
        return $this->view('emails.email-verification')
            ->from(env('MAIL_FROM_ADDRESS'))
            ->subject('【Statements】会員仮登録完了のお知らせ')
            ->with([
                'token' => $this->emailVerification->token
            ]);
    }
}
