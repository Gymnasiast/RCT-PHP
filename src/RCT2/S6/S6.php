<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT12\Research\List\ResearchLists;
use RCTPHP\RCT2\S6\Chunks\AvailableItemsChunk;
use RCTPHP\RCT2\S6\Chunks\Chunk;
use RCTPHP\RCT2\S6\Chunks\HeaderChunk;
use RCTPHP\RCT2\S6\Chunks\TileElementsChunk;
use RCTPHP\RCT2\S6\Chunks\TimeChunk;
use function str_ends_with;
use function strtolower;

final class S6
{
    public function __construct(
        public readonly HeaderChunk $headerChunk,
        public readonly AvailableItemsChunk $availableItemsChunk,

        // For now
        public readonly ResearchLists $researchLists
    ) {
    }

    public static function createFromFilename(string $filename): self
    {
        $isScenario = str_ends_with(strtolower($filename), '.sc6');
        $reader = \Cyndaron\BinaryHandler\BinaryReader::fromFile($filename);

        return self::createFromReader($reader, $isScenario);
    }

    private static function readSV6(BinaryReader $reader): self
    {
        $headerChunk = \RCTPHP\RCT2\S6\Chunks\HeaderChunk::fromReader($reader);

        // Skip past all of them
        for ($i = 0; $i < $headerChunk->numPackedObjects; $i++)
        {
            $datHeader = \RCTPHP\RCT2\Object\DATHeader::tryFromReader($reader);
            $chunkHeader = \RCTPHP\RCT2\S6\Chunks\ChunkHeader::createFromReader($reader);
            $reader->seek($chunkHeader->length);
        }

        $availableItemsChunk = AvailableItemsChunk::fromReader($reader);

        $timeChunk = TimeChunk::fromReader($reader);
        $tileElementsChunk = TileElementsChunk::fromReader($reader);

        $chunk6 = Chunk::createFromReader($reader);

        $secondReader = \Cyndaron\BinaryHandler\BinaryReader::fromString($chunk6->decodedContents);
        $secondReader->moveTo(0x27248C);

        $hydrator = new \RCTPHP\RCT2\Research\Hydrator($secondReader, 500);
        $entries = $hydrator->readAllEntries();
        $list = \RCTPHP\RCT12\Research\List\ResearchLists::createFromResearchItemList($entries);

        return new self($headerChunk, $availableItemsChunk, $list);
    }

    private static function readSC6(BinaryReader $reader): self
    {
        $headerChunk = \RCTPHP\RCT2\S6\Chunks\HeaderChunk::fromReader($reader);
        // Read info chunk
        $chunk1 = Chunk::createFromReader($reader);

        // Skip past all of them
        for ($i = 0; $i < $headerChunk->numPackedObjects; $i++)
        {
            $datHeader = \RCTPHP\RCT2\Object\DATHeader::tryFromReader($reader);
            $chunkHeader = \RCTPHP\RCT2\S6\Chunks\ChunkHeader::createFromReader($reader);
            $reader->seek($chunkHeader->length);
        }

        $availableItemsChunk = AvailableItemsChunk::fromReader($reader);

        $timeChunk = TimeChunk::fromReader($reader);
        $tileElementsChunk = TileElementsChunk::fromReader($reader);

        $chunk6 = Chunk::createFromReader($reader);
        $chunk7 = Chunk::createFromReader($reader);
        $chunk8 = Chunk::createFromReader($reader);
        $chunk9 = Chunk::createFromReader($reader);
        $chunk10 = Chunk::createFromReader($reader);
        $chunk11 = Chunk::createFromReader($reader);
        $chunk12 = Chunk::createFromReader($reader);
        $chunk13 = Chunk::createFromReader($reader);

        $secondReader = \Cyndaron\BinaryHandler\BinaryReader::fromString($chunk13->decodedContents);
        $secondReader->moveTo(0x104);

        $hydrator = new \RCTPHP\RCT2\Research\Hydrator($secondReader, 500);
        $entries = $hydrator->readAllEntries();
        $list = \RCTPHP\RCT12\Research\List\ResearchLists::createFromResearchItemList($entries);

        return new self($headerChunk, $availableItemsChunk, $list);
    }

    public static function createFromReader(BinaryReader $reader, bool $isScenario): self
    {
        if ($isScenario)
        {
            return self::readSC6($reader);
        }
        return self::readSV6($reader);
    }
}
