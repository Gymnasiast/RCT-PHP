<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

use function number_format;

final class SawyerPrice
{
    public function __construct(public readonly int $internal)
    {
    }

    public function asGBP(): string
    {
        return '£' . number_format($this->internal / 10, 2);
    }
}
