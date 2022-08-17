<?php

namespace App\Services;

use App\Repositories\MemberRepository;

use App\Values\SearchQuery;

class MemberService {
    /**
     * @var MemberRepository
     */
    protected $memberRepository;

    /**
     * MemberRepository constructor
     * @param MemberRepository $memberRepository
     */
    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    public function search(SearchQuery $searchQuery, $sort)
    {
        return $this->memberRepository->search($searchQuery, $sort);
    }

    public function find($id)
    {
        return $this->memberRepository->find($id);
    }

    public function findBy($field, $operator, $value)
    {
        return $this->memberRepository->findBy($field, $operator, $value);
    }

    public function existsByMail($mail)
    {
        return $this->memberRepository->existsByMail($mail);
    }

    public function create($data)
    {
        return $this->memberRepository->create($data);
    }
}