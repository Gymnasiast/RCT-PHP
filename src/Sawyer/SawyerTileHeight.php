<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

use function floor;

final class SawyerTileHeight
{
    public function __construct(public readonly int $internal)
    {
    }

    public function asMetres(): int
    {
        return (int)($this->internal * 3 / 4);
    }

    public function asMetresFormatted(): string
    {
        return $this->asMetres() . ' m';
    }

    public function asFeet(): int
    {
        return (int)floor(($this->asMetres() * 840) / 256);
    }

    public function asFeetFormatted(): string
    {
        return $this->asFeet() . ' ft';
    }

    public function __toString()
    {
        return (string)$this->internal;
    }
}
