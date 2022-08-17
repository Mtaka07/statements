<?php

namespace App\Emuns;

use BenSampo\Enum\Enum;

final class EmailVerificationStatus extends Enum
{
    //仮会員登録のメール
    const SEND_MAIL = 0;

    //メールアドレス確認
    const MAIL_VERIFY = 1;

    //本会員登録完了
    const REGISTER = 2;
}