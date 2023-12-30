<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research\Entry;

use RCTPHP\RCT1\Research\ResearchPriority;
use RCTPHP\RCT1\RideType;
use RCTPHP\RCT12\Research\Entry\ResearchEntry;

class RideEntry implements ResearchEntry
{
    public function __construct(public readonly RideType $type, public readonly ResearchPriority $researchPriority)
    {
    }
}
