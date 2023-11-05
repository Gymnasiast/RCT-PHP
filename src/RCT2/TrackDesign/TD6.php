<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT12\EntranceStyle;
use RCTPHP\RCT12\TrackDesignSpeed;
use RCTPHP\RCT12\TrackDesignVersion;
use RCTPHP\RCT12\VehicleColorScheme;
use RCTPHP\RCT2\Color;
use RCTPHP\RCT2\Limits;
use RCTPHP\RCT2\Object\DATHeader;
use RCTPHP\RCT2\OperatingMode;
use RCTPHP\RCT2\RideStatictics;
use RCTPHP\RCT2\RideType;
use RCTPHP\RCT2\TrackColor;
use RCTPHP\RCT2\VehicleColor;
use RCTPHP\Sawyer\RLE\RLEString;
use RCTPHP\Sawyer\SawyerTileHeight;
use RCTPHP\Util;

final class TD6
{
    private const MAX_TRAINS_PER_RIDE = 32;

    public RideType $rideType;
    public int $specialFeatures;
    public OperatingMode $operatingMode;
    public VehicleColorScheme $vehicleColorScheme;
    public TrackDesignVersion $version;
    /** @var VehicleColor[] */
    public array $vehicleColors = [];
    public EntranceStyle $entranceStyle;

    public int $totalAirTime;
    public int $departureControlFlags;
    public int $numberOfTrains;
    public int $carsPerTrain;
    public int $minimumWaitingTime;
    public int $maximumWaitingTime;
    // Additional operating setting. May contain speed, number of rotations, ...
    public int $operatingSetting;
    public RideStatictics $statictics;
    public int $upkeepCost;

    /** @var TrackColor[] */
    public array $trackColors = [];

    public SpecialFeatures2 $specialFeatures2;

    public DATHeader $vehicle;

    public int $spaceRequiredX;
    public int $spaceRequiredY;
    public int $liftHillSpeed;
    public int $numCircuits;

    public function __construct(BinaryReader $reader)
    {
        $this->readHeader($reader);
    }

    private function readHeader(BinaryReader $reader)
    {
        $this->rideType = RideType::from($reader->readUint8());
        // Room for the vehicle type index
        $reader->seek(1);
        $this->specialFeatures = $reader->readUint32();
        $this->operatingMode = OperatingMode::from($reader->readUint8());

        $colorSchemeAndVersion = $reader->readUint8();
        $this->vehicleColorScheme = VehicleColorScheme::from($colorSchemeAndVersion & 0b00000011);
        $this->version = TrackDesignVersion::from($colorSchemeAndVersion >> 2);

        for ($i = 0; $i < self::MAX_TRAINS_PER_RIDE; $i++)
        {
            $bodyColor = Color::from($reader->readUint8());
            $trimColor = Color::from($reader->readUint8());

            // The additional color will be imported later.
            $this->vehicleColors[$i] = new VehicleColor($bodyColor, $trimColor, Color::BLACK);
        }

        // Padding
        $reader->seek(1);

        $this->entranceStyle = EntranceStyle::from($reader->readUint8());
        $this->totalAirTime = $reader->readUint8();
        $this->departureControlFlags = $reader->readUint8();
        $this->numberOfTrains = $reader->readUint8();
        $this->carsPerTrain = $reader->readUint8();
        $this->minimumWaitingTime = $reader->readUint8();
        $this->maximumWaitingTime = $reader->readUint8();
        $this->operatingSetting = $reader->readUint8();

        $this->statictics = $this->readStatistics($reader);

        $this->upkeepCost = $reader->readUint16();

        $this->trackColors = $this->readTrackColors($reader);

        $this->specialFeatures2 = new SpecialFeatures2($reader->readUint32());

        $this->vehicle = new DATHeader($reader);

        $this->spaceRequiredX = $reader->readUint8();
        $this->spaceRequiredY = $reader->readUint8();

        for ($i = 0; $i < self::MAX_TRAINS_PER_RIDE; $i++)
        {
            $this->vehicleColors[$i]->additional = Color::from($reader->readUint8());
        }

        $liftHillSpeedAndNumCircuits = $reader->readUint8();
        $this->liftHillSpeed = $liftHillSpeedAndNumCircuits & 0b00011111;
        $this->numCircuits = $liftHillSpeedAndNumCircuits >> 5;
    }

    /**
     * @param BinaryReader $reader
     * @return TrackColor[]
     */
    private function readTrackColors(BinaryReader $reader): array
    {
        $trackSpineColors = [];
        $trackRailColors = [];
        $trackSupportColors = [];
        $ret = [];

        for ($i = 0; $i < Limits::NUM_COLOR_SCHEMES; $i++)
        {
            $trackSpineColors[$i] = Color::from($reader->readUint8());
        }
        for ($i = 0; $i < Limits::NUM_COLOR_SCHEMES; $i++)
        {
            $trackRailColors[$i] = Color::from($reader->readUint8());
        }
        for ($i = 0; $i < Limits::NUM_COLOR_SCHEMES; $i++)
        {
            $trackSupportColors[$i] = Color::from($reader->readUint8());
        }
        for ($i = 0; $i < Limits::NUM_COLOR_SCHEMES; $i++)
        {
            $ret[] = new TrackColor($trackSpineColors[$i], $trackRailColors[$i], $trackSupportColors[$i]);
        }

        return $ret;
    }

    private function readStatistics(BinaryReader $reader): RideStatictics
    {
        $statistics = new RideStatictics();
        $statistics->maximumSpeed = new TrackDesignSpeed($reader->readUint8());
        $statistics->averageSpeed = new TrackDesignSpeed($reader->readUint8());
        $statistics->length = $reader->readUint16();
        $statistics->maximumPositiveG = $reader->readUint8();
        $statistics->maximumNegativeG = $reader->readSint8();
        $statistics->maximumLateralG = $reader->readUint8();
        // The upper 3 bits _may_ contain information about how much of the ride is covered.
        $statistics->numInversionsOrHoles = $reader->readUint8() & 0b00011111;
        $statistics->numDrops = $reader->readUint8() & 0b00111111;
        $statistics->highestDropHeight = new SawyerTileHeight($reader->readUint8());
        $statistics->excitement = $reader->readUint8() / 10;
        $statistics->intensity = $reader->readUint8() / 10;
        $statistics->nausea = $reader->readUint8() / 10;

        return $statistics;
    }

    public static function createFromFile(string $filename): self
    {
        $reader = BinaryReader::fromFile($filename);
        return self::createFromCompressedStream($reader);
    }

    public static function createFromCompressedStream(BinaryReader $stream): self
    {
        $size = $stream->getSize();
        // Skip the checksum at the end
        $undecoded = $stream->readBytes($size - 4);
        $decoded = (new RLEString($undecoded))->decode();

        $reader = BinaryReader::fromString($decoded);
        return new self($reader);
    }
}
