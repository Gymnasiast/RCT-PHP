<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class WaterObject extends BaseObject
{
    public WaterProperties $properties;

    public function __construct(WaterProperties $properties = new WaterProperties(true))
    {
        $this->objectType = ObjectType::WATER;
        $this->properties = $properties;
    }
}
