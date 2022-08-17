<?php

namespace App\Values;

use App\Enums\MemberStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Member
{
    protected $apiToken;
    protected $name = "";
    protected $mail = "";
    protected $password;

    public function __construct()
    {
        $this->apiToken = Str::random(60);
    }

    public function getNewApiToken()
    {
        return $this->apiToken;
    }

    public function getMakeHashPassword($password)
    {
        return Hash::make($password);
    }

    public function getNewLastLogin()
    {
        return Carbon::now();
    }

    public function createNewData()
    {
        return [
            \App\Models\Member::NAME => $this->name,
            \App\Models\Member::MAIL => $this->mail,
            \App\Models\Member::PASSWORD => self::getMakeHashPassword($this->password),
            \App\Models\Member::STATUS => MemberStatus::NORMAL,
            \App\Models\Member::API_TOKEN => self::getNewApiToken()
        ];
    }

    public function setName($value)
    {
        return $this->name = $value;
    }

    public function setMail($value)
    {
        return $this->mail = $value;
    }

    public function setPassword($password)
    {
        return $this->password = $password;
    }
}