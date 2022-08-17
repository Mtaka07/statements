<?php

namespace App\Services;

use App\Repositories\EmailVerificationRepository;

class EmailVerificationService 
{
    /**
     * @var EmailVerificationRepository
     */
    protected $emailVerificationRepository;

    /**
     * EmailVerificationService constructer
     * @param EmailVerificationRepository $emailVerificationRepository
     */
    public function __construct(EmailVerificationRepository $emailVerificationRepository)
    {
        $this->emailVerificationRepository = $emailVerificationRepository;
    }

    /**
     *すでに登録済みか確認
     */
    public function isRegisteredByEmail($email)
    {
        return $this->emailVerificationRepository->isRegisteredByEmail($email);
    }

    /**
     * @param $token
     * @return bool
     */
    public function canRegistration($token)
    {
        return $this->emailVerificationRepository->canRegistration($token);
    }


    public function find($id) 
    {
        return $this->emailVerificationRepository->find($id);
    }

    public function findBy($field, $operator, $value)
    {
        return $this->emailVerificationRepository->findBy($field, $operator, $value);
    }

    public function findAll()
    {
        return $this->emailVerificationRepository->findAll();
    }

    public function findAllBy($field, $operator, $value)
    {
        return $this->emailVerificationRepository->findAllBy($field, $operator, $value);
    }

    public function updateById($id, $data)
    {
        return $this->emailVerificationRepository->updateById($id, $data);
    }

    public function create($data)
    {
        return $this->emailVerificationRepository->create($data);
    }

    public function canVerificationMailByToken($token)
    {
        return $this->emailVerificationRepository->canVerificationMailByToken($token);
    }

    public function updateByToken($token, $data)
    {
        return $this->emailVerificationRepository->updateByToken($token, $data);
    }
}