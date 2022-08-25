<?php

namespace App\Repositories;

use App\Models\Member;

use App\Values\SearchQuery;

class MemberRepository {
    /**
     * @var Member
     */
    protected $member;

    const ASC = "asc";
    const DESC = 'desc';

    /**
     * MemberRepository constructor
     * @param Member $member
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }

    /**
     * @param SearchQuery $serchQuery
     * @return \Illuminate\Contracts\Pagenation\LengthAwarePaginator
     */
    public function search(SearchQuery $searchQuery, $sort)
    {
        $members = $this->member->newQuery();
        $queries = $searchQuery->getQueries();
        foreach ($queries as $query) {
            if(!is_null($query[$searchQuery::VALUE])) {
                $members = $members->where(
                    $query[$searchQuery->columnKey()],
                    $query[$searchQuery->operatorKey()],
                    $query[$searchQuery->valueKey()]
                );
            }
        }
        if($sort == 'desc') {
            return $members->orderBy(Member::ID, self::DESC)->pagenate(10);
        }else {
            return $members->orderBy(Member::ID, self::ASC)->paginate(10);
        }
    }

    public function find($id)
    {
        return $this->member
            ->newQuery()
            ->find($id);
    }

    public function findBy($field, $operator, $value)
    {
        return $this->member
            ->where($field, $operator, $value);
    }

    public function existsByMail($mail)
    {
        return $this->member
            ->newQuery()
            ->where(Member::MAIL, $mail)
            ->exists();
    }

    public function create($data)
    {
        return $this->member
            ->newQuery()
            ->create($data);
    }

    public function updateById($id,$data)
    {
        return $this->member
            ->newQuery()
            ->find($id)
            ->fill($data)
            ->save();
    }

}