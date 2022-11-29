<?php
declare(strict_types=1);

namespace RCTPHP\Object\OpenRCT2;

final class SceneryGroupObject extends BaseObject
{
    public function __construct()
    {
        $this->objectType = ObjectType::SCENERY_GROUP;
    }

    public array $properties = [];
    public array $images = [];
}
