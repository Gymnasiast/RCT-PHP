<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class MusicObject extends BaseObject
{
    public function __construct()
    {
        $this->objectType = ObjectType::MUSIC;
    }

    public array $properties = [];
    public array $images = [];
}
