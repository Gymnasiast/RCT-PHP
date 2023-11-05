<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\TrackDesign;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT1\Color;
use RCTPHP\RCT1\Limits;
use RCTPHP\RCT1\OperatingMode;
use RCTPHP\RCT1\RideStatictics;
use RCTPHP\RCT1\RideType;
use RCTPHP\RCT1\TrackColor;
use RCTPHP\RCT1\VehicleColor;
use RCTPHP\RCT1\VehicleType;
use RCTPHP\RCT12\TrackDesignSpeed;
use RCTPHP\RCT12\TrackDesignVersion;
use RCTPHP\RCT12\VehicleColorScheme;
use RCTPHP\Sawyer\RLE\RLEString;
use RCTPHP\Util;

final class TD4
{
    public RideType $rideType;
    public VehicleType $vehicleType;
    public int $specialFeatures;
    public OperatingMode $operatingMode;
    public VehicleColorScheme $vehicleColorScheme;
    public TrackDesignVersion $version;
    /** @var VehicleColor[] */
    public array $vehicleColors = [];
    /** @var TrackColor[] */
    public array $trackColors = [];

    public int $departureControlFlags;
    public int $numberOfTrains;
    public int $carsPerTrain;
    public int $minimumWaitingTime;
    public int $maximumWaitingTime;
    // Additional operating setting. May contain speed, number of rotations, ...
    public int $operatingSetting;

    public RideStatictics $statictics;
    public int $upkeepCost;

    public int $specialFeatures2 = 0;

    public function __construct(BinaryReader $reader)
    {
        $this->readHeader($reader);
    }

    private function readHeader(BinaryReader $reader): void
    {
        $this->rideType = RideType::from($reader->readUint8());
        $this->vehicleType = VehicleType::from($reader->readUint8());
        $this->specialFeatures = $reader->readUint32();
        $this->operatingMode = OperatingMode::from($reader->readUint8());

        $colorSchemeAndVersion = $reader->readUint8();
        $this->vehicleColorScheme = VehicleColorScheme::from($colorSchemeAndVersion & 0b00000011);
        $this->version = TrackDesignVersion::from($colorSchemeAndVersion >> 2);

        for ($i = 0; $i < Limits::MAX_TRAINS_PER_RIDE; $i++)
        {
            $bodyColor = Color::from($reader->readUint8());
            $trimColor = Color::from($reader->readUint8());

            $this->vehicleColors[$i] = new VehicleColor($bodyColor, $trimColor);
        }

        // For AA/LL, this will be overwritten later on.
        $this->trackColors = $this->readTrackColors($reader, 1);

        $this->departureControlFlags = $reader->readUint8();
        $this->numberOfTrains = $reader->readUint8();
        $this->carsPerTrain = $reader->readUint8();
        $this->minimumWaitingTime = $reader->readUint8();
        $this->maximumWaitingTime = $reader->readUint8();
        $this->operatingSetting = $reader->readUint8();

        $this->statictics = $this->readStatistics($reader);

        $this->upkeepCost = $reader->readUint16();

        if ($this->version = TrackDesignVersion::RCT1_BASE)
        {
            return;
        }

        $this->trackColors = $this->readTrackColors($reader, Limits::NUM_COLOR_SCHEMES);
        $this->specialFeatures2 = $reader->readUint8();

        // Skip padding
        $reader->seek(0x7F);
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

    /**
     * @param BinaryReader $reader
     * @param int $amount
     * @return TrackColor[]
     */
    private function readTrackColors(BinaryReader $reader, int $amount): array
    {
        $trackSpineColors = [];
        $trackRailColors = [];
        $trackSupportColors = [];
        $ret = [];

        for ($i = 0; $i < $amount; $i++)
        {
            $trackSpineColors[$i] = Color::from($reader->readUint8());
        }
        for ($i = 0; $i < $amount; $i++)
        {
            $trackRailColors[$i] = Color::from($reader->readUint8());
        }
        for ($i = 0; $i < $amount; $i++)
        {
            $trackSupportColors[$i] = Color::from($reader->readUint8());
        }
        for ($i = 0; $i < $amount; $i++)
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
        $statistics->highestDropHeight = new Height($reader->readUint8());
        $statistics->excitement = $reader->readUint8() / 10;
        $statistics->intensity = $reader->readUint8() / 10;
        $statistics->nausea = $reader->readUint8() / 10;

        return $statistics;
    }
}
