<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Coordinates;

final class MediumVertical
{
    public function __construct(
        public readonly int $value
    ) {
    }

    public function toTinyVertical(): TinyVertical
    {
        return new TinyVertical($this->value / 8);
    }

    public function toSmallVertical(): SmallVertical
    {
        return new SmallVertical($this->value / 2);
    }

    public function toBigVertical(): BigVertical
    {
        return new BigVertical($this->value * 4);
    }
}
