<?php

namespace App\Values;

use App\Enums\EmailVerificationStatus;
use Carbon\Carbon;
use Illuminate\Support\Str;

class EmailVerification
{
    protected $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * @return array
     */
    public function create()
    {
        return [
            \App\Models\EmailVerification::EMAIL => $this->email,
            \App\Models\EmailVerification::TOKEN => $this->getToken(),
            \App\Models\EmailVerification::STATUS => EmailVerificationStatus::SEND_MAIL,
            \App\Models\EmailVerification::EXPIRATION_DATETIME => Carbon::now()->addHours(1),
        ];
    }

    public function getToken()
    {
        return Str::random(60);
    }
}