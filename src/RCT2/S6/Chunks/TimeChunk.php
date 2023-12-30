<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;

final class TimeChunk
{
    use ChunkToString;

    public function __construct(
        public readonly int $elapsedMonths,
        public readonly int $currentDay,
        public readonly int $scenarioTicks,
        public readonly int $srand0,
        public readonly int $srand1,
    ) {
    }

    public static function fromString(string $input): self
    {
        $reader = BinaryReader::fromString($input);
        $elapsedMonths = $reader->readUint16();
        $currentDay = $reader->readUint16();
        $scenarioTicks = $reader->readUint32();
        $srand0 = $reader->readUint32();
        $srand1 = $reader->readUint32();

        return new self($elapsedMonths, $currentDay, $scenarioTicks, $srand0, $srand1);
    }
}
