<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;

final class HeaderChunk
{
    use ChunkToString;

    public function __construct(
        public readonly int $type,
        public readonly int $classicFlag,
        public readonly int $numPackedObjects,
        public readonly int $version,
        public readonly int $magicNumber,
    ) {
    }

    public static function fromString(string $input): self
    {
        $reader = BinaryReader::fromString($input);
        $type = $reader->readUint8();
        $classicFlag = $reader->readUint8();
        $numPackedObjects = $reader->readUint16();
        $version = $reader->readUint32();
        $magicNumber = $reader->readUint32();

        return new self($type, $classicFlag, $numPackedObjects, $version, $magicNumber);
    }
}
