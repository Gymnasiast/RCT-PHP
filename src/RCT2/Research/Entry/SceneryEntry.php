<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Research\Entry;

use RCTPHP\RCT12\Research\Entry\ResearchEntry;

class SceneryEntry implements ResearchEntry
{
    public function __construct(public readonly int $index)
    {
    }
}
