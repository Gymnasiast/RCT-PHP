<?php
declare(strict_types=1);

namespace RCTPHP\Wave;

use Cyndaron\BinaryHandler\BinaryReader;

final class Header
{
    public const SIZE = 16;

    public readonly int $formatTag;
    public readonly int $channels;
    public readonly int $samplesPerSec;
    public readonly int $avgBytesPerSec;
    public readonly int $blockAlign;
    public readonly int $bitsPerSample;

    public function __construct(BinaryReader $reader)
    {
        $this->formatTag = $reader->readUint16();
        $this->channels = $reader->readUint16();
        $this->samplesPerSec = $reader->readUint32();
        $this->avgBytesPerSec = $reader->readUint32();
        $this->blockAlign = $reader->readUint16();
        $this->bitsPerSample = $reader->readUint16();
    }
}
