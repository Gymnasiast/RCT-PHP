<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Research;

enum ResearchPriority : int
{
    case TRANSPORT_RIDES = 0;
    case GENTLE_RIDES = 1;
    case ROLLER_COASTERS = 2;
    case THRILL_RIDES = 3;
    case WATER_RIDES = 4;
    case SHOPS = 5;
    case SCENERY_GROUPS = 6;
}
