<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Coordinates;

final class BigHorizontal
{
    public function __construct(
        public readonly int $value
    ) {
    }

    public function toSmallHorizontal(): SmallHorizontal
    {
        return new SmallHorizontal($this->value / 32);
    }
}
