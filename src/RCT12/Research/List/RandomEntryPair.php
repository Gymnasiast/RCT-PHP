<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Research\List;

use RCTPHP\RCT12\Research\Entry\ResearchEntry;

final class RandomEntryPair
{
    public function __construct(public readonly ResearchEntry $item1, public readonly ResearchEntry $item2)
    {
    }
}
