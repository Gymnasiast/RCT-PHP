<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\Coordinates;

final class BigVertical
{
    public function __construct(
        public readonly int $value
    ) {
    }

    public function toTinyVertical(): TinyVertical
    {
        return new TinyVertical($this->value / 16);
    }

    public function toSmallVertical(): SmallVertical
    {
        return new SmallVertical($this->value / 8);
    }

    public function toMediumVertical(): MediumVertical
    {
        return new MediumVertical($this->value / 4);
    }
}
