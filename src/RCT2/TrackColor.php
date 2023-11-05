<?php
declare(strict_types=1);

namespace RCTPHP\RCT2;

use RCTPHP\RCT2\Color;

final class TrackColor
{
    public function __construct(
        public Color $spine,
        public Color $rail,
        public Color $support,
    ) {
    }
}
