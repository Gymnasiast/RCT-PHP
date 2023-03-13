<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\ImageTable;

use RCTPHP\Util\RGB;
use function array_key_exists;

final class Palette
{
    /**
     * @param int $index
     * @param int $numColors
     * @param RGB[] $colors
     */
    public function __construct(
        public readonly int $index,
        public readonly int $numColors,
        public array $colors = [],
    )
    {
        for ($i = 0; $i < $this->numColors; $i++)
        {
            if (!array_key_exists($i, $this->colors))
            {
                $this->colors[$i] = new RGB(0, 0, 0);
            }
        }
    }
}
