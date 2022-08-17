<?php

namespace App\Http\Middleware;

use Closure;
use App\Enums\ResponseCode;
use App\Models\Member;
use App\Services\MemberService;
use Illuminate\Http\Request;

class CheckMember
{
    /**
     * @var MemberService
     */
    protected $memberService;

    /**
     * @param MemberService $memberService
     */
    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiToken = $request->bearerToken();
        $member = $this->memberService->findBy(Member::API_TOKEN, "=", $apiToken)->first();

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
