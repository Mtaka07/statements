<?php

namespace App\Http\Controllers;

use App\Enums\EmailVerificationStatus;
use App\Enums\ResponseCode;
use App\Values\Member;
use App\Values\EmailVerification;
use Illuminate\Http\Request;
use App\Services\MemberService;
use App\Services\EmailVerificationService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;


class EmailVerificationController extends Controller
{
    /**
     * @var MemberService
     */
    protected $memberService;
    /**
     * @var EmailVerificationService
     */
    protected $emailVerificationService;

    /**
     * EmailVerificationController constructor
     * @param MemberService $memberService
     * @param EmailVerificationService $emailVerificationService
     */
    public function __construct(MemberService $memberService, EmailVerificationService $emailVerificationService)
    {
        $this->memberService = $memberService;
        $this->emailVerificationService = $emailVerificationService;
    }

    /**
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function temporaryRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => "required|email",
        ]);

        if($validator->fails()) {
            return response()->json([
                "result" => false,
                "status" => ResponseCode::ERROR,
                "message" => "メールアドレスが正しくありません。",
                "data" => null,
            ],ResponseCode::SUCCESS);
        }
        $email = $request->get("email");
        $isRegistered = $this->emailVerificationService->isRegisteredByEmail($email);
        $isMember = $this->memberService->existsByMail($email);
        $errorMessage = "このアドレスは既に登録済みです。";

        DB::beginTransaction();
        try {
            //既に登録済みでないか確認
            if(!$isRegistered && !$isMember && $email) {
                //仮認証登録
                $emailVerificationValue = new EmailVerification($email);
                $emailVerification = $this->emailVerificationService->create($emailVerificationValue->create());

                Mail::to($email)->send(new \App\Mail\EmailVerification($emailVerification));

                DB::commit();
                return response()->json([
                    "result" => true,
                    "status" => ResponseCode::SUCCESS,
                    "message" => "仮登録しました。",
                    "data" => $emailVerification,
                ],ResponseCode::SUCCESS);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::info($exception);
            $errorMessage = $exception->getMessage();
        }

        return response()->json([
            "result" => false,
            "status" => ResponseCode::ERROR,
            "message" => $errorMessage,
            "data" => null,
        ],ResponseCode::SUCCESS);
    }

    /**
     * メール認証
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function emailVerification(Request $request)
    {
        $token = $request->get("token");

        //トークンチェック
        $canVerificationMail = $this->emailVerificationService->canVerificationMailByToken($token);

        //既に登録済みでないか確認と有効期限ないかどうか
        if($canVerificationMail) {
            //メール認証済みに更新
            $this->emailVerificationService->updateByToken([
                \App\Models\EmailVerification::STATUS => EmailVerificationStatus::MAIL_VERIFY
            ], $token);

            $emailVerification = $this->emailVerificationService->findBy(\App\Models\EmailVerification::TOKEN, '=', $token)->first();

            return response()->json([
                "result" => true,
                "status" => ResponseCode::SUCCESS,
                "message" => "メールアドレスの確認ができました。",
                "data" => $emailVerification,
            ],ResponseCode::SUCCESS);
        }
        return response()->json([
            "result" => false,
            "sttaus" => ResponseCode::ERROR,
            "message" => "確認できないトークンです。",
            "data" => null,
        ],ResponseCode::SUCCESS);
    }

    /**
     * 本登録
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function definitiveRegistration(Request $request)
    {
        $token = $request->get("token");
        $email = $request->get("email");
        $name = $request->get("name");
        $password = $request->get("password");

        $canRegistration = $this->emailVerificationService->canRegistration($token);
        $isMember = $this->memberService->existsByMail($email);

        //本登録
        if($canRegistration && !$isMember) {
            //登録完了に変更
            $this->emailVerificationService->updateByToken([
                \App\Models\EmailVerification::STATUS => EmailVerificationStatus::REGISTER,
                \App\Models\EmailVerification::EMAIL_VERIFIED_AT => Carbon::now()
            ], $token);

            //メンバー登録
            $memberValue = new Member();
            $memberValue->setMail($email);
            $memberValue->setName($name);
            $memberValue->setPassword($password);
            $member = $this->memberService->create($memberValue->createNewData());

            $memberId = optional($member)->id;

            return response()->json([
                "result" => true,
                "status" => ResponseCode::SUCCESS,
                "message" => "本登録完了しました。",
                "data" => null,
            ],ResponseCode::SUCCESS);
        }
        return response()->json([
            "result" => false,
            "status" => ResponseCode::ERROR,
            "message" => "本登録完了できません。",
            "data" => null,
        ],ResponseCode::SUCCESS);
    }
}
