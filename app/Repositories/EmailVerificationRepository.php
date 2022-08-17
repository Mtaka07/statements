<?php

namespace App\Repositories;

use App\Enums\EmailVerificationStatus;
use App\Models\EmailVerification;
use Carbon\Carbon;

class EmailVerificationRepository
{
    /**
     * @var EmailVerification
     */
    protected $emailVerification;

    /**
     * EmailVerificationRepository constructer
     * @param EmailVerification $emailVerification
     */
    public function __construct(EmailVerification $emailVerification)
    {
        $this->emailVerification = $emailVerification;
    }

    /**
     * すでに登録済みかどうか確認
     * @param $emailVerification
     * @return bool
     */
    public function isRegisteredByEmail($email)
    {
        return $this->emailVerification
            ->newQuery()
            ->where(EmailVerification::EMAIL, '=', $email)
            ->where(EmailVerification::STATUS, '=', EmailVerificationStatus::REGISTER)
            ->exists();
    }

    /**
     * @param $token
     * @return bool
     */
    public function canRegistration($token)
    {
        return $this->emailVerification
            ->where(EmailVerification::token, '=', $token)
            ->where(EmailVerification::status, '=', EmailVerificationStatus::MAIL_VERIFY)
            ->exists();
    }

    /**
     * @param $id
     */
    public function find($id)
    {
        return $this->emailVerification
            ->newQuery()
            ->find($id);
    }

    /**
     * @param $field
     * @param $value
     * @param $operator
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findBy($field, $value, $operator)
    {
        return $this->emailVerification
            ->newQuery()
            ->where($field, $value, $operator);
    }

    /**
     * 
     */
    public function findAll()
    {
        return $this->emailVerification
            ->newQuery()
            ->get();
    }

    /**
     * @param $field
     * @param $operator
     * @param $value
     */
    public function findAllBy($field, $operator, $value)
    {
        return $this->emailVerification
            ->newQuery()
            ->where($field, $operator, $value)
            ->get();
    }

    /**
     * @param $id
     * @param $data
     * @return bool
     */
    public function updateById($id, $data)
    {
        return $this->emailVerification
            ->newQuery()
            ->find($id)
            ->fill($data)
            ->save();
    }

    /**
     * @param $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    public function create($data)
    {
        return $this->emailVerification
            ->newQuery()
            ->create($data);
    }

    /**
     * @param $token
     * @return bool
     */
    public function canVerificationMailByToken($token)
    {
        return $this->emailVerification
            ->newQuery()
            ->where(EmailVerification::TOKEN, $token)
            ->whereIn(EmailVerification::STATUS, [EmailVerificationStatus::SEND_MAIL, EmailVerificationStatus::MAIL_VERIFY])
            ->where(EmailVerification::EXPIRATION_DATETIME, ">=", Carbon::now())
            ->exists();
    }

    /**
     * @param $data
     * @param $token
     * @return int
     */
    public function updateByToken($data, $token)
    {
        return $this->emailVerification
            ->newQuery()
            ->where(EmailVerification::TOKEN, $token)
            ->update($data);
    }
}