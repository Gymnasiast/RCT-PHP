<?php
declare(strict_types=1);

namespace RCTPHP\Wave;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use RCTPHP\Util\Reader\ReadableInterface;
use RCTPHP\Util\Reader\TryFromReaderTrait;

final class Header implements ReadableInterface
{
    use TryFromReaderTrait;

    public const SIZE = 16;

    public function __construct(
        public readonly int $formatTag,
        public readonly int $channels,
        public readonly int $samplesPerSec,
        public readonly int $avgBytesPerSec,
        public readonly int $blockAlign,
        public readonly int $bitsPerSample,
    ) {
    }

    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): self
    {
        $formatTag = $reader->readUint16();
        $channels = $reader->readUint16();
        $samplesPerSec = $reader->readUint32();
        $avgBytesPerSec = $reader->readUint32();
        $blockAlign = $reader->readUint16();
        $bitsPerSample = $reader->readUint16();

        return new self($formatTag, $channels, $samplesPerSec, $avgBytesPerSec, $blockAlign, $bitsPerSample);
    }
}
