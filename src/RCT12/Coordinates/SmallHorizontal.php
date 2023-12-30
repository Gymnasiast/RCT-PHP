<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Coordinates;

final class SmallHorizontal
{
    public function __construct(
        public readonly int $value
    ) {
    }

    public function toBigHorizontal(): BigHorizontal
    {
        return new BigHorizontal($this->value * 32);
    }
}
