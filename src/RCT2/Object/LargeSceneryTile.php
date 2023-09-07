<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

class LargeSceneryTile
{
    public function __construct(
        public readonly int $xOffset,
        public readonly int $yOffset,
        public readonly int $zOffset,
        public readonly int $zClearance,
        public readonly int $flags,
    )
    {
    }
}
