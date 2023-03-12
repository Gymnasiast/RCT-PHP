<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\ImageTable;

use function array_fill;

final class PalettizedImage
{
    private array $lines;

    public function __construct(public readonly int $width, public readonly int $height)
    {
        $this->lines = array_fill(0, $height, array_fill(0, $width, 0));
    }

    public function getPixel(int $x, int $y): int
    {
        return $this->lines[$y][$x];
    }

    public function setPixel(int $x, int $y, int $value): void
    {
        $this->lines[$y][$x] = $value;
    }
}
