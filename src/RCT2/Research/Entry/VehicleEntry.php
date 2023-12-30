<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Research\Entry;

use RCTPHP\RCT2\Research\ResearchPriority;
use RCTPHP\RCT2\RideType;
use RCTPHP\RCT12\Research\Entry\ResearchEntry;

class VehicleEntry implements ResearchEntry
{
    public function __construct(public readonly RideType $rideType, public readonly int $index, public readonly ResearchPriority $researchPriority)
    {
    }
}
