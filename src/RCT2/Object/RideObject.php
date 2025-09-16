<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use GdImage;
use RCTPHP\RCT2\Color;
use RCTPHP\RCT2\RideType;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\WithPreview;

class RideObject implements RCT2Object, StringTableOwner, ImageTableOwner, WithPreview
{
    public const MAX_CAR_TYPES = 4;

    public const RIDE_ENTRY_FLAG_DISABLE_COLOR_TAB = 1 << 19;

    use DATFromFile;
    use StringTableDecoder;

    private DATHeader $header;


    public int $flags;
    public RideType|null $assocRide0;
    public RideType|null $assocRide1;
    public RideType|null $assocRide2;
    public int $minCarsPerTrain;
    public int $maxCarsPerTrain;
    public int $carsPerFlatRide;
    public int $numZeroCars;
    public int $tabCar;
    public int $defaultCar;
    public int $frontCar;
    public int $secondCar;
    public int $rearCar;
    public int $thirdCar;
    /** @var RideCarObject[] */
    public array $cars;
    public int $excitementMultiplier;
    public int $intensityMultiplier;
    public int $nauseaMultiplier;
    public int $maxHeight;
    public int $shopItem0;
    public int $shopItem1;
    public int $numPresetColors;

    public bool $differentColorPerTrain = false;
    public array $peepLoadingWaypoints;
    public array $peepLoadingPositions;
    public array $presetColors = [];

    /** @var array<string, StringTable> */
    public array $stringTable = [];

    private ImageTable $imageTable;

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(8);

        $this->flags = $reader->readUint32();
        $this->assocRide0 = RideType::tryFrom($reader->readUint8());
        $this->assocRide1 = RideType::tryFrom($reader->readUint8());
        $this->assocRide2 = RideType::tryFrom($reader->readUint8());

        $this->minCarsPerTrain = $reader->readUint8();
        $this->maxCarsPerTrain = $reader->readUint8();
        $this->carsPerFlatRide = $reader->readUint8();
        $this->numZeroCars = $reader->readUint8();
        $this->tabCar = $reader->readUint8();
        $this->defaultCar = $reader->readUint8();
        $this->frontCar = $reader->readUint8();
        $this->secondCar = $reader->readUint8();
        $this->rearCar = $reader->readUint8();
        $this->thirdCar = $reader->readUint8();

        // Skip Pad019
        $reader->seek(1);

        $this->cars = [];
        for ($i = 0; $i < self::MAX_CAR_TYPES; $i++)
        {
            $this->cars[] = $this->readCar($reader);
        }
        $reader->seek(4);
        $this->excitementMultiplier = $reader->readSint8();
        $this->intensityMultiplier = $reader->readSint8();
        $this->nauseaMultiplier = $reader->readSint8();
        $this->maxHeight = $reader->readUint8();
        // Skipping a uint64_t for the enabled track pieces and two uint8_ts for the categories.
        $reader->seek(10);
        $this->shopItem0 = $reader->readUint8();
        $this->shopItem1 = $reader->readUint8();

        $this->readStringTable($reader, 'name');
        $this->readStringTable($reader, 'description');
        $this->readStringTable($reader, 'capacity');

        // Read preset colors, by default there are 32
        $this->numPresetColors = $reader->readUint8();

        // To indicate a ride has different colors each train the count
        // is set to 255. There are only actually 32 colors though.
        if ($this->numPresetColors == 255)
        {
            $this->differentColorPerTrain = true;
            $this->numPresetColors = 32;
        }

        for ($i = 0; $i < $this->numPresetColors; $i++)
        {
            $body = $reader->readUint8();
            $trim = $reader->readUint8();
            $tertiary = $reader->readUint8();

            $this->presetColors[$i] = [$body, $trim, $tertiary];
        }

        // TODO: should probably be part of the code that converts between RCT2 and OpenRCT2 objects.
        if ($this->assocRide0->isShopOrFacility())
        {
            // This used to be hard-coded. JSON objects set this themselves.
            $this->numPresetColors = 1;
            $this->presetColors[0] = [Color::BRIGHT_RED, Color::BRIGHT_RED, Color::BRIGHT_RED];

            if ($this->assocRide0 == RideType::FOOD_STALL || $this->assocRide0 == RideType::DRINK_STALL)
            {
                // In RCT2, no food or drink stall could be recolored.
                $this->flags |= self::RIDE_ENTRY_FLAG_DISABLE_COLOR_TAB;
            }
        }

        // Read peep loading positions
        for ($i = 0; $i < self::MAX_CAR_TYPES; $i++)
        {
            $this->peepLoadingWaypoints[$i] = [];
            $this->peepLoadingPositions[$i] = [];

            $numPeepLoadingPositions = $reader->readUint8();
            if ($numPeepLoadingPositions == 255)
            {
                $numPeepLoadingPositions = $reader->readUint16();
            }

            if ($this->cars[$i]->flags & RideCarObject::CAR_ENTRY_FLAG_LOADING_WAYPOINTS)
            {
                $this->cars[$i]->peepLoadingWaypointSegments = $reader->readSint8() == 0 ? 0 : 4;
                if ($this->assocRide0 == RideType::ENTERPRISE)
                {
                    $this->cars[$i]->peepLoadingWaypointSegments = 8;
                }

                assert((($numPeepLoadingPositions - 1) % 8) == 0, "Malformed peep loading positions");

                for ($j = 1; $j < $numPeepLoadingPositions; $j += 4 * 2)
                {
                    // All "big coords"
                    $x0 = $reader->readSint8();
                    $y0 = $reader->readSint8();
                    $x1 = $reader->readSint8();
                    $y1 = $reader->readSint8();
                    $x2 = $reader->readSint8();
                    $y2 = $reader->readSint8();
                    $reader->readUint16();

                    $this->peepLoadingWaypoints[$i][] = [$x0, $y0, $x1, $y1, $x2, $y2];
                }
            }
            else
            {
                $this->cars[$i]->peepLoadingWaypointSegments = 0;

                $this->peepLoadingPositions = [];
                for ($ak = 0; $ak < $numPeepLoadingPositions; $ak++)
                {
                    $this->peepLoadingPositions[] = $reader->readSint8();
                }
            }
        }

        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));
    }

    function readCar(BinaryReader $reader): RideCarObject
    {
        $car = new RideCarObject();
        $car->tabRotationMask = $reader->readUint16();
        $reader->seek(2 * 1);
        $car->spacing = $reader->readUint32();
        $car->carMass = $reader->readUint16();
        $car->tabHeight = $reader->readSint8();
        $car->numSeats = $reader->readUint8();
        $car->spriteGroups = $reader->readUint16();
        $car->spriteWidth = $reader->readUint8();
        $car->spriteHeightPegative = $reader->readUint8();
        $car->spriteHeightPositive = $reader->readUint8();
        $car->animation = $reader->readUint8();
        $car->flags = $reader->readUint32();

        $car->baseNumFrames = $reader->readUint16();
        $reader->seek(15 * 4);
        $car->numSeatingRows = $reader->readUint8();
        $car->spinningInertia = $reader->readUint8();
        $car->spinningFriction = $reader->readUint8();
        $car->frictionSoundId = $reader->readUint8();
        $car->reversedCarIndex = $reader->readUint8();
        $car->soundRange = $reader->readUint8();
        $car->doubleSoundFrequency = $reader->readUint8();
        $car->poweredAcceleration = $reader->readUint8();
        $car->poweredMaxSpeed = $reader->readUint8();
        $car->paintStyle = $reader->readUint8();
        $car->effectVisual = $reader->readUint8();
        $car->drawOrder = $reader->readUint8();
        $car->numVerticalFramesOverride = $reader->readUint8();
        $reader->seek(4);

        return $car;
    }

    public function getPreview(): GdImage
    {
        if ($this->assocRide0 !== null)
            return $this->imageTable->gdImageData[0];
        else if ($this->assocRide1 !== null)
            return $this->imageTable->gdImageData[1];
        else if ($this->assocRide2 !== null)
            return $this->imageTable->gdImageData[2];

        return $this->imageTable->gdImageData[0];
    }
}