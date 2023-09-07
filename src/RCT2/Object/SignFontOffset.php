<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

class SignFontOffset
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    )
    {
    }
}
