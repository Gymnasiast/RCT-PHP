<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use RCTPHP\Util;
use RCTPHP\Util\Reader\TryFromReaderTrait;

final class DATDetector extends \RCTPHP\Sawyer\Object\DATDetector
{
    use TryFromReaderTrait;

    public const OBJECT_MAPPING = [
        DATHeader::OBJECT_TYPE_INTERFACE => InterfaceObject::class,
        DATHeader::OBJECT_TYPE_SOUNDS => SoundObject::class,
        DATHeader::OBJECT_TYPE_CURRENCY => CurrencyObject::class,
        DATHeader::OBJECT_TYPE_TRACK => TrackObject::class,
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
