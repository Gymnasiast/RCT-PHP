<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;

final class MusicObject extends BaseObject
{
    /** @var array<string, mixed> */
    public array $properties = [];
    public ImageTable $images;

    public function __construct()
    {
        $this->objectType = ObjectType::MUSIC;
    }
}
