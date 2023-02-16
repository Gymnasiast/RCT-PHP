<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

enum ObjectType : string
{
    case MUSIC = 'music';
    case SCENERY_GROUP = 'scenery_group';
    case WATER = 'water';
}
