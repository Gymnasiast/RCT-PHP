<?php
declare(strict_types=1);

namespace RCTPHP\Util\PCX;

enum PCXVersion : int
{
    case V2_5 = 0;
    case V2_8_WITH_PALETTE = 2;
    case V2_8_WITHOUT_PALETTE = 3;
    case PAINTBRUSH_FOR_WINDOWS = 4;
    case V3_0 = 5;
}
