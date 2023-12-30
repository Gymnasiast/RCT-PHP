<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

use RCTPHP\RCT12\Coordinates\SmallHorizontal;
use RCTPHP\RCT12\Coordinates\SmallVertical;

class EntranceElement
{
    public function __construct(
        public readonly SmallHorizontal $x,
        public readonly SmallHorizontal $y,
        public readonly SmallVertical $z,
        public readonly int $direction,
        public readonly bool $isExit,
    ) {
    }
}
