<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

final class SpecialFeatures2
{
    public const IS_SIX_FLAGS = 1 << 31;
    public const HAS_REVERSER = 1 << 2;

    public function __construct(public readonly int $internalValue)
    {
    }

    private function isFlagSet(int $flag): bool
    {
        return (bool)($this->internalValue & $flag);
    }

    public function isSixFlags(): bool
    {
        return $this->isFlagSet(self::IS_SIX_FLAGS);
    }

    public function hasReverser(): bool
    {
        return $this->isFlagSet(self::HAS_REVERSER);
    }
}
