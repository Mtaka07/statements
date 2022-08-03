<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class SessionKeys extends Enum
{
    const LAST_MEMBER_PAGE = 'last_member_page';
}
