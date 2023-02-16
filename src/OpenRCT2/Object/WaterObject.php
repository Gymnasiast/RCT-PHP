<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class WaterObject extends BaseObject
{
    public function __construct()
    {
        $this->objectType = ObjectType::WATER;
        $this->properties = new WaterProperties(true);
    }

    public WaterProperties $properties;
}
