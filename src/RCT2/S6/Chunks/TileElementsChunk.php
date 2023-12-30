<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;

final class TileElementsChunk
{
    use ChunkToString;

    public function __construct(
        /** @var string[] */
        public readonly array $tileElements
    ) {
    }

    public static function fromString(string $decoded): self
    {
        $reader = BinaryReader::fromString($decoded);
        $tileElements = [];
        for ($i = 0; $i < 0x30000; $i++)
        {
            $tileElements[] = $reader->readBytes(8);
        }

        return new self($tileElements);
    }
}
