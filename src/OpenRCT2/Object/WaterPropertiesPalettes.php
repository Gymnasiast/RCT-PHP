<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class WaterPropertiesPalettes
{
    public function __construct(
        public WaterPropertiesPalettesPart $general = new WaterPropertiesPalettesPart(10),
        public WaterPropertiesPalettesPart $waves_0 = new WaterPropertiesPalettesPart(16),
        public WaterPropertiesPalettesPart $waves_1 = new WaterPropertiesPalettesPart(32),
        public WaterPropertiesPalettesPart $waves_2 = new WaterPropertiesPalettesPart(48),
        public WaterPropertiesPalettesPart $sparkles_0 = new WaterPropertiesPalettesPart(80),
        public WaterPropertiesPalettesPart $sparkles_1 = new WaterPropertiesPalettesPart(96),
        public WaterPropertiesPalettesPart $sparkles_2 = new WaterPropertiesPalettesPart(112),
    )
    {
    }
}
