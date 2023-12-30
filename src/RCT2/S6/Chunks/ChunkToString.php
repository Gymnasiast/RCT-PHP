<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;

trait ChunkToString
{
    public static function fromReader(BinaryReader $reader): self
    {
        $chunk = Chunk::createFromReader($reader);
        return self::fromChunk($chunk);
    }

    public static function fromChunk(Chunk $chunk): self
    {
        return self::fromString($chunk->decodedContents);
    }
}
