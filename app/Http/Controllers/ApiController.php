<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Enums\ResponseCode;
use App\Services\MemberService;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /** MemberService */
    protected $memberService;

    /**
     * ApiController constructor 
     * @param MemberService $memberService
     */
    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function login(LoginRequest $request)
    {
        $id = request()->input('id');
        $mail = request()->input('mail');
        $password = request()->input('password');

        Log::info("id".$id);

        $member = Member::where('mail',$mail)
            ->where('status', \App\Enums\MemberStatus::NORMAL)
            ->first();
        
        if(($member && Hash::check($password, $member->password)) || $member) {
            Log::info('auth OK');
            $memberValue = new \App\Values\Member();
            //一時的なログイン実装するとき
            Auth::loginUsingId($member->id, false);
            $this->memberService->updateById(optional($member)->id, [
                Member::API_TOKEN => $memberValue->getNewApiToken()
            ]);

            $member->api_token = $memberValue->getNewApiToken();

            return response()->json([
                'result' => true,
                'status' => ResponseCode::SUCCESS,
                'message' => "OK",
                'data' => $member,
            ],ResponseCode::SUCCESS);
        }
        return response()->json([
            'result' => false,
            'status' => ResponseCode::ERROR,
            'message' => 'メールアドレス、パスワードが違います',
        ],ResponseCode::SUCCESS);
    }

}
