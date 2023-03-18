<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Util;
use TXweb\BinaryHandler\BinaryReader;

final class DATDetector extends \RCTPHP\Sawyer\Object\DATDetector
{
    public const OBJECT_MAPPING = [
        DATHeader::OBJECT_TYPE_WALLS => WallObject::class,
        DATHeader::OBJECT_TYPE_SCENERY_GROUP => SceneryGroupObject::class,
        DATHeader::OBJECT_TYPE_WATER => WaterObject::class,
        DATHeader::OBJECT_TYPE_SCENARIO_TEXT => ScenarioTextObject::class,
    ];

    public function __construct(BinaryReader $reader)
    {
        $this->header = new DATHeader($reader);
        $this->rest = Util::readChunk($reader);
    }
}
