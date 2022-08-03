<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class MemberStatus extends Enum
{
    const TEMPORARY =   0;
    const NORMAL =   1;
    const WITHDRAWAL = 2;
    const STOP = 3;
}
