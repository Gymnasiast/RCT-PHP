<?php
declare(strict_types=1);

namespace RCTPHP\RCT12;

final class TrackDesignSpeed
{
    public function __construct(public int $rawValue)
    {
    }

    public function toRCTInternal(): int
    {
        return ((($this->rawValue << 16) * 9) >> 18);
    }

    public function asMph(): int
    {
        return $this->toRCTInternal();
    }

    public function asKmh(): int
    {
        return ($this->asMph() * 1648) >> 10;
    }
}
