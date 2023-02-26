<?php
declare(strict_types=1);

namespace RCTPHP\Wave;

use RCTPHP\Binary;

final class Header
{
    public const SIZE = 16;

    public readonly int $formatTag;
    public readonly int $channels;
    public readonly int $samplesPerSec;
    public readonly int $avgBytesPerSec;
    public readonly int $blockAlign;
    public readonly int $bitsPerSample;

    /**
     * @param resource $fp
     */
    public function __construct($fp)
    {
        $this->formatTag = Binary::readUint16($fp);
        $this->channels = Binary::readUint16($fp);
        $this->samplesPerSec = Binary::readUint32($fp);
        $this->avgBytesPerSec = Binary::readUint32($fp);
        $this->blockAlign = Binary::readUint16($fp);
        $this->bitsPerSample = Binary::readUint16($fp);
    }
}
