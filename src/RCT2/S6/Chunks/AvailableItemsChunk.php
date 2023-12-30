<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT2\Object\DATHeader;

final class AvailableItemsChunk
{
    use ChunkToString;

    public function __construct(
        /** @var list<DatHeader|null> */
        public readonly array $vehicles,
        /** @var list<DatHeader|null> */
        public readonly array $smallScenery,
        /** @var list<DatHeader|null> */
        public readonly array $largeScenery,
        /** @var list<DatHeader|null> */
        public readonly array $walls,
        /** @var list<DatHeader|null> */
        public readonly array $banners,
        /** @var list<DatHeader|null> */
        public readonly array $paths,
        /** @var list<DatHeader|null> */
        public readonly array $pathAdditions,
        /** @var list<DatHeader|null> */
        public readonly array $sceneryGroups,
        public readonly DATHeader|null $parkEntrance,
        public readonly DATHeader|null $water,
        public readonly DATHeader|null $stex,
    ) {
    }

    public static function fromString(string $input): self
    {
        $reader = BinaryReader::fromString($input);

        $vehicles = [];
        for ($i = 0; $i < 128; $i++)
        {
            $vehicles[$i] = DATHeader::tryFromReader($reader);
        }

        $smallScenery = [];
        for ($i = 0; $i < 252; $i++)
        {
            $smallScenery[$i] = DATHeader::tryFromReader($reader);
        }

        $largeScenery = [];
        for ($i = 0; $i < 128; $i++)
        {
            $largeScenery[$i] = DATHeader::tryFromReader($reader);
        }

        $walls = [];
        for ($i = 0; $i < 128; $i++)
        {
            $walls[$i] = DATHeader::tryFromReader($reader);
        }

        $banners = [];
        for ($i = 0; $i < 32; $i++)
        {
            $banners[$i] = DATHeader::tryFromReader($reader);
        }

        $paths = [];
        for ($i = 0; $i < 16; $i++)
        {
            $paths[$i] = DATHeader::tryFromReader($reader);
        }

        $pathAdditions = [];
        for ($i = 0; $i < 15; $i++)
        {
            $pathAdditions[$i] = DATHeader::tryFromReader($reader);
        }

        $sceneryGroups = [];
        for ($i = 0; $i < 19; $i++)
        {
            $sceneryGroups[$i] = DATHeader::tryFromReader($reader);
        }

        $parkEntrance = DATHeader::tryFromReader($reader);
        $water = DATHeader::tryFromReader($reader);
        $stex = DATHeader::tryFromReader($reader);

        return new self($vehicles, $smallScenery, $largeScenery, $walls, $banners, $paths, $pathAdditions, $sceneryGroups, $parkEntrance, $water, $stex);
    }
}
