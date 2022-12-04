<?php
declare(strict_types=1);

namespace RCTPHP\Object\OpenRCT2;

final class WaterObject extends BaseObject
{
    public function __construct()
    {
        $this->objectType = ObjectType::WATER;
        $this->properties = new WaterProperties(true);
    }

    public WaterProperties $properties;
}
