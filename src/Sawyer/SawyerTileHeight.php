<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

final class SawyerTileHeight
{
    public function __construct(public readonly int $internal)
    {
    }

    public function asMetres(): string
    {
        return ($this->internal * 3 / 4) . ' m';
    }

    public function asFeet(): string
    {
        return ($this->internal * 10 / 4) . ' ft';
    }

    public function __toString()
    {
        return (string)$this->internal;
    }
}
