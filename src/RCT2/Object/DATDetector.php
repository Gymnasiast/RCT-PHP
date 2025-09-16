<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use RCTPHP\Util;
use RCTPHP\Util\Reader\TryFromReaderTrait;

final class DATDetector extends \RCTPHP\Sawyer\Object\DATDetector
{
    use TryFromReaderTrait;

    public const OBJECT_MAPPING = [
        DATHeader::OBJECT_TYPE_RIDE => null,
        DATHeader::OBJECT_TYPE_SMALL_SCENERY => SmallSceneryObject::class,
        DATHeader::OBJECT_TYPE_LARGE_SCENERY => LargeSceneryObject::class,
        DATHeader::OBJECT_TYPE_WALLS => WallObject::class,
        DATHeader::OBJECT_TYPE_BANNERS => BannerObject::class,
        DATHeader::OBJECT_TYPE_PATHS => PathObject::class,
        DATHeader::OBJECT_TYPE_PATH_ADDITIONS => PathAdditionObject::class,
        DATHeader::OBJECT_TYPE_SCENERY_GROUP => SceneryGroupObject::class,
        DATHeader::OBJECT_TYPE_PARK_ENTRANCE => ParkEntranceObject::class,
        DATHeader::OBJECT_TYPE_WATER => WaterObject::class,
        DATHeader::OBJECT_TYPE_SCENARIO_TEXT => ScenarioTextObject::class,
    ];

    private DATHeader $header;

    public function __construct(DATHeader $header, string $rest)
    {
        $this->header = $header;
        $this->rest = $rest;
    }

    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): self
    {
        $header = DATHeader::fromReader($reader);
        $rest = Util::readChunk($reader);

        return new self($header, $rest);
    }

    public function getHeader(): DATHeader
    {
        return $this->header;
    }
}
