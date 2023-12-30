<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research\Entry;

use RCTPHP\RCT1\Research\SceneryType;
use RCTPHP\RCT12\Research\Entry\ResearchEntry;

class SceneryEntry implements ResearchEntry
{
    public function __construct(public readonly SceneryType $type)
    {
    }
}
