<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Enums\Sessionkeys;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Services\MemberService;
use App\Values\SearchQuery;
use App\Http\Requests\MemberRequest;
use App\Illuminate\Support\Facades\Auth;
use App\Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
    /** @var MemberService */
    protected $memberService;

    /**
     * MemberController constructor.
     * @param MemberService $memberService
     */
    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(MemberRequest $request)
    {
        $request->session()->put(Sessionkeys::LAST_MEMBER_PAGE, url()->full());
        $sort = $request->get('searchSort');

        $searchQuery = new SearchQuery();
        $searchQuery->setQuery(
            Member::NAME,
            'like',
            '%' . addcslashes($request->name(), '%_\\') . '%'
        );
        $searchQuery->setQuery(
            Member::ID,
            '=',
            $request->memberId()
        );
        $searchQuery->setQuery(
            Member::MAIL,
            'like',
            '%' . addcslashes($request->mail(), '%_\\') . '%'
        );
        $searchQuery->setQuery(
            Member::STATUS,
            '=',
            $request->status()
        );

        $members = $this->memberService->search($searchQuery, $sort);
        $members = json_encode($members);
        $constants = json_encode(config('const'));

        return view('home', compact('members', 'constants'));
    }
}
