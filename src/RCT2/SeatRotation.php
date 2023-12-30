<?php
declare(strict_types=1);

namespace RCTPHP\RCT2;

enum SeatRotation : int
{
    case MINUS_180_DEGREES = 0;
    case MINUS_135_DEGREES = 1;
    case MINUS_90_DEGREES = 2;
    case MINUS_45_DEGREES = 3;
    case NONE = 4;
    case PLUS_45_DEGREES = 5;
    case PLUS_90_DEGREES = 6;
    case PLUS_135_DEGREES = 7;
    case PLUS_180_DEGREES = 8;
    case PLUS_225_DEGREES = 9;
    case PLUS_270_DEGREES = 10;
    case PLUS_315_DEGREES = 11;
    case PLUS_360_DEGREES = 12;
    case PLUS_405_DEGREES = 13;
    case PLUS_450_DEGREES = 14;
    case PLUS_495_DEGREES = 15;
}
