<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class SceneryGroupObject extends BaseObject
{
    public function __construct()
    {
        $this->objectType = ObjectType::SCENERY_GROUP;
        $this->properties = new SceneryGroupProperties();
    }

    public SceneryGroupProperties $properties;
    public array $images = [];
}