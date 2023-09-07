<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;

final class DATDetector extends \RCTPHP\Sawyer\Object\DATDetector
{
    public const OBJECT_MAPPING = [
        DATHeader::OBJECT_TYPE_SMALL_SCENERY => SmallSceneryObject::class,
        DATHeader::OBJECT_TYPE_LARGE_SCENERY => LargeSceneryObject::class,
        DATHeader::OBJECT_TYPE_WALLS => WallObject::class,
        DATHeader::OBJECT_TYPE_SCENERY_GROUP => SceneryGroupObject::class,
        DATHeader::OBJECT_TYPE_WATER => WaterObject::class,
        DATHeader::OBJECT_TYPE_SCENARIO_TEXT => ScenarioTextObject::class,
    ];

    private DATHeader $header;

    public function __construct(BinaryReader $reader)
    {
        $this->header = new DATHeader($reader);
        $this->rest = Util::readChunk($reader);
    }

    public function getHeader(): DATHeader
    {
        return $this->header;
    }
}
