<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research;

enum ResearchPriority : int
{
    case ROLLER_COASTERS = 0;
    case THRILL_RIDES = 1;
    case GENTLE_AND_TRANSPORT_RIDES = 2;
    case SHOPS = 3;
    case SCENERY = 4;
    case RIDE_IMPROVEMENTS = 5;
}
