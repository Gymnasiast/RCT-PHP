<?php
declare(strict_types=1);

namespace RCTPHP\RCT2;

final class VehicleColor
{
    public function __construct(
        public Color $body,
        public Color $trim,
        public Color $additional,
    ) {
    }
}
