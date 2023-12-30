<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\Sawyer\ChunkEncoding;

final class ChunkHeader
{
    public function __construct(public readonly ChunkEncoding $encoding, public readonly int $length)
    {
    }

    public static function createFromReader(BinaryReader $reader): self
    {
        $encoding = ChunkEncoding::from($reader->readUint8());
        $length = $reader->readUint32();
        return new self($encoding, $length);
    }
}
