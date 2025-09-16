<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class WaterObject extends BaseObject
{
    public const WAVE_START = 230;
    public const SPARKLE_START = 235;
    public const NUM_ANIMATED_WATER_FRAMES = 15;

    public WaterProperties $properties;

    public function __construct(WaterProperties $properties = new WaterProperties(true))
    {
        $this->objectType = ObjectType::WATER;
        $this->properties = $properties;
    }
}
