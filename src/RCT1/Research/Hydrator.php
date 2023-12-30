<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT1\Research\Entry\RideEntry;
use RCTPHP\RCT1\Research\Entry\RideImprovementEntry;
use RCTPHP\RCT1\Research\Entry\SceneryEntry;
use RCTPHP\RCT1\Research\Entry\VehicleEntry;
use RCTPHP\RCT1\RideType;
use RCTPHP\RCT1\VehicleType;
use RCTPHP\RCT12\Research\ResearchItem;
use RCTPHP\RCT12\Research\Separator\EndOfListMarker;
use RCTPHP\RCT12\Research\Separator\StartRandomSeparator;
use RCTPHP\RCT12\Research\Separator\StartUninventedSeparator;

final class Hydrator
{
    private const RESEARCH_ITEM_SIZE = 5;

    public function __construct(private readonly BinaryReader $reader, private readonly int $numEntries)
    {
    }

    /**
     * @return ResearchItem[]
     */
    public function readAllEntries(): array
    {
        $ret = [];
        for ($i = 0; $i < $this->numEntries; $i++)
        {
            $entry = $this->readEntry();
            $ret[] = $entry;
            if ($entry instanceof EndOfListMarker)
            {
                $bytesToSkip = ($this->numEntries - $i - 1) * self::RESEARCH_ITEM_SIZE;
                $this->reader->seek($bytesToSkip);
                break;
            }
        }

        return $ret;
    }

    public function readEntry(): ResearchItem
    {
        $index = $this->reader->readUint8();
        $rideIndex = $this->reader->readUint8();
        $type = $this->reader->readUint8();
        $flags = $this->reader->readUint8();
        $expenditureArea = $this->reader->readUint8();
        $rawValue = $index | ($rideIndex << 8) | ($type << 16) | ($flags << 24);

        if ($rawValue === 0xFFFFFFFF)
        {
            return new StartUninventedSeparator();
        }
        if ($rawValue === 0xFFFFFFFE)
        {
            return new StartRandomSeparator();
        }
        if ($rawValue === 0xFFFFFFFD)
        {
            return new EndOfListMarker();
        }

        $type = ResearchType::from($type);
        switch ($type)
        {
            case ResearchType::THEMING:
                return new SceneryEntry(SceneryType::from($index));
            case ResearchType::RIDES:
                return new RideEntry(RideType::from($index), ResearchPriority::from($expenditureArea));
            case ResearchType::VEHICLES:
                return new VehicleEntry(RideType::from($rideIndex), VehicleType::from($index), ResearchPriority::from($expenditureArea));
            case ResearchType::RIDE_IMPROVEMENTS:
                return new RideImprovementEntry(RideType::from($rideIndex), RideImprovementType::from($index));
        }

        throw new \RuntimeException('Could not classify item!');
    }
}
