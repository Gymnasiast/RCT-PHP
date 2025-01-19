<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

enum SourceGame : int
{
    case RCT2 = 8;
    case WW = 1;
    case TT = 2;
    case CUSTOM = 0;
}