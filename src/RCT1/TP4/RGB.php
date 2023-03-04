<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\TP4;

final class RGB
{
    public function __construct(
        public readonly int $r,
        public readonly int $g,
        public readonly int $b,
    )
    {}
}
