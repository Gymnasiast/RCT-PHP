<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class WaterPropertiesPalettesPart
{
    /**
     * @param int $index
     * @param string[] $colours
     */
    public function __construct(
        public int $index,
        public array $colours = [],
    )
    {

    }
}
