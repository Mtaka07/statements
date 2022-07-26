<?php

namespace App\Http\Middleware;

use App\Enums\ResponseCode;
use App\Models\Member;
use App\Services\MemberService;
use Closure;
use Illuminate\Http\Request;

class CheckMember
{
    /** * @var MemberService */
    protected $memberService;

    /**
     * CheckMember constructor
     * @param MemberService $memberService
     */
    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * Handle an incoming request.
     * 
     * @param \Illuminate\Http\Request $_REQUEST
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->bearerToken();
        $member = $this->memberService->findBy(Member::API_TOKEN, $apiToken, '=')->first();

        if(!($apiToken && $member)) {
            return response()->json([
                'result' => false,
                'status' => ResponseCode::ERROR,
                'message' => 'Check Member Fail'
            ],ResponseCode::SUCCESS);
        }

        return $next($request);
    }
}
