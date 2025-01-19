<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;

final class SmallSceneryObject extends BaseObject
{
    /** @var ImageTable|list<array{path: string}> */
    public ImageTable|array $images;

    public function __construct()
    {
        $this->objectType = ObjectType::SMALL_SCENERY;
    }
}
