<?php
declare(strict_types=1);

namespace RCTPHP\RCT1;

final class VehicleColor
{
    public function __construct(
        public Color $body,
        public Color $trim,
    ) {
    }
}
