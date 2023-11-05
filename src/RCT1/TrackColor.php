<?php
declare(strict_types=1);

namespace RCTPHP\RCT1;

final class TrackColor
{
    public function __construct(
        public Color $spine,
        public Color $rail,
        public Color $support,
    ) {
    }
}
