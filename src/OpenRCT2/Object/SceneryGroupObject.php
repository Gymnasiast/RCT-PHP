<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;

final class SceneryGroupObject extends BaseObject
{
    public SceneryGroupProperties $properties;
    /** @var ImageTable|list<array{path: string}> */
    public ImageTable|array $images;

    public function __construct()
    {
        $this->objectType = ObjectType::SCENERY_GROUP;
        $this->properties = new SceneryGroupProperties();
    }
}
