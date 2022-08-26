<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Enums\Sessionkeys;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Services\MemberService;
use App\Values\SearchQuery;
use App\Http\Requests\MemberRequest;
use App\Http\Requests\MemberEditRequest;
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

    public function show(Request $request, $id)
    {
        $lastMemberPage = $request->session()->get(SessionKeys::LAST_MEMBER_PAGE,route('member.index'));

        $member = $this->memberService->find($id);

        return view('member-details', compact("member","lastMemberPage"));
    }

    public function create(Request $request)
    {
        $lastMemberPage = $request->session()->get(Sessionkeys::LAST_MEMBER_PAGE,route('member.index'));

        $member = null;

        return view("member-edit",compact("member","lastMemberPage"));
    }

    public function edit(Request $request, $id)
    {
        $lastMemberPage = $request->session()->get(Sessionkeys::LAST_MEMBER_PAGE,route('member.index'));

        $member = $this->memberService->find($id);

        return view("member-edit",compact("lastMemberPage", "member"));
    }

    public function save(MemberEditRequest $request)
    {
        $id = $request->input('id');
        $data = $request->updateData();
        $memberValue = new \App\Values\Member();
        $memberData = [
            'name' => $request->input('name'),
            'mail' => $request->input('mail'),
            'status' => $request->input('status'),
            'password' => $memberValue->getMakeHashPassword($request->input('password')),
        ];
        $this->memberService->updateOrCreate(['id' => $id],$memberData);

        return redirect()->route('member.index');
    }

    public function member(Request $request)
    {
        $apiToken = $request->bearerToken();
        $member = $this->memberService->findBy(Member::API_TOKEN, "=", $apiToken)->first();

        return response()->json([
            "result" => true,
            "status" => ResponseCode::SUCCESS,
            "message" => "OK",
            "data" => $member
        ],ResponseCode::SUCCESS);
    }
}
