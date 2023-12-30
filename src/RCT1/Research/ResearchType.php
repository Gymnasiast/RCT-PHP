<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research;

enum ResearchType : int
{
    case THEMING = 0;
    case RIDES = 1;
    case VEHICLES = 2;
    case RIDE_IMPROVEMENTS = 3;
}
