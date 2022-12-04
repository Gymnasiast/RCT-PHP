<?php
declare(strict_types=1);

namespace RCTPHP\Object\OpenRCT2;

final class WaterProperties
{
    public function __construct(
        public bool $allowDucks,
        public WaterPropertiesPalettes $palettes = new WaterPropertiesPalettes(),
    )
    {
    }
}
