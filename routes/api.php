<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//会員登録
Route::prefix("member")->group(function () {
    //仮登録
    Route::post('/temporary_registration', [App\Http\Controllers\EmailVerificationController::class, 'temporaryRegistration']);
    //メール認証
    Route::post('/email_verification', [App\Http\Controllers\EmailVerificationController::class, 'emailVerification']);
    //本登録
    Route::post('/definitive_registration', [App\Http\Controllers\EmailVerificationController::class, 'definitiveRegistration']);
});

Route::middleware(["cors"])->group(function () {
    Route::get('/login', [App\Http\Controllers\ApiController::class, 'index']);
    Route::post('/login', [App\Http\Controllers\ApiController::class, 'login']);

    Route::prefix("member")->group(function () {
        Route::middleware(["check.member"])->group(function () {
            //メンバー情報取得
            Route::get('/', [App\Http\Controllers\MemberController::class, 'member']);
        });
    });
});
