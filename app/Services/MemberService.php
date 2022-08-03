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
}