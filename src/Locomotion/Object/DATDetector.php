<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;

final class DATDetector extends \RCTPHP\Sawyer\Object\DATDetector
{
    public const OBJECT_MAPPING = [
        DATHeader::OBJECT_TYPE_INTERFACE => InterfaceObject::class,
        DATHeader::OBJECT_TYPE_SOUNDS => SoundObject::class,
        DATHeader::OBJECT_TYPE_CURRENCY => CurrencyObject::class,
        DATHeader::OBJECT_TYPE_TRACK => TrackObject::class,
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
