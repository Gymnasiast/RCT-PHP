<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research\Entry;

use RCTPHP\RCT1\Research\ResearchPriority;
use RCTPHP\RCT1\RideType;
use RCTPHP\RCT1\VehicleType;
use RCTPHP\RCT12\Research\Entry\ResearchEntry;

class VehicleEntry implements ResearchEntry
{
    public function __construct(public readonly RideType $rideType, public readonly VehicleType $type, public readonly ResearchPriority $researchPriority)
    {
    }
}
