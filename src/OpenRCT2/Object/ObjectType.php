<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

enum ObjectType : string
{
    case LARGE_SCENERY = 'scenery_large';
    case MUSIC = 'music';
    case SCENERY_GROUP = 'scenery_group';
    case SMALL_SCENERY = 'scenery_small';
    case WALL = 'scenery_wall';
    case WATER = 'water';
}
