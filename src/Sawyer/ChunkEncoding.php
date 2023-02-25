<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

enum ChunkEncoding : int
{
    case NONE = 0;
    case RLE = 1;
    case RLE_REPEAT = 2;
    case ROTATE = 3;
}
