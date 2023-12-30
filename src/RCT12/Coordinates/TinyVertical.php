<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Coordinates;

final class TinyVertical
{
    public function __construct(
        public readonly int $value
    ) {
    }

    public function toSmallVertical(): SmallVertical
    {
        return new SmallVertical($this->value * 2);
    }

    public function toMediumVertical(): MediumVertical
    {
        return new MediumVertical($this->value * 4);
    }

    public function toBigVertical(): BigVertical
    {
        return new BigVertical($this->value * 16);
    }
}
