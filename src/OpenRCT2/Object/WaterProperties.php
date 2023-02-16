<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class WaterProperties
{
    public function __construct(
        public bool $allowDucks,
        public WaterPropertiesPalettes $palettes = new WaterPropertiesPalettes(),
    )
    {
    }
}
