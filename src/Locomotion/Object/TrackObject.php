<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;
use function strlen;
use function implode;
use function array_map;

class TrackObject implements LocomotionObject, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];

    /** @var DATHeader[] */
    public array $compatibleTracksRoads = [];
    /** @var DATHeader[] */
    public array $mods = [];
    /** @var DATHeader[] */
    public array $signals = [];
    public ?DATHeader $tunnel = null;
    /** @var DATHeader[] */
    public array $bridges = [];
    /** @var DATHeader[] */
    public array $stations = [];
    private readonly ImageTable $imageTable;


    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(0x02); // Space for the name;
        $trackPieces = $reader->readUint16();
        $stationTrackPieces = $reader->readUint16();
        $var06 = $reader->readUint8();
        $numCompatible = $reader->readUint8();
        $numMods = $reader->readUint8();
        $numSignals = $reader->readUint8();
        $mods = [];
        for ($i = 0; $i < 4; $i++)
        {
            $mods[$i] = $reader->readUint8();
        }
        $signals = $reader->readUint16();
        $compatibleTracks = $reader->readUint16();
        $compatibleRoads = $reader->readUint16();
        $buildCostFactor = $reader->readSint16();
        $sellCostFactor = $reader->readSint16();
        $tunnelCostFactor = $reader->readSint16();
        $costIndex = $reader->readUint8();
        $tunnel = $reader->readUint8();
        $curveSpeed = $reader->readUint16();
        $image = $reader->readUint32();
        $trackObjectFlags = $reader->readUint16();
        $numBridges = $reader->readUint8();
        $bridges = [];
        for ($i = 0; $i < 7; $i++)
        {
            $bridges[$i] = $reader->readUint8();
        }
        $numStations = $reader->readUint8();
        $stations = [];
        for ($i =0; $i < 7; $i++)
        {
            $stations[$i] = $reader->readUint8();
        }
        $displayOffset = $reader->readUint8();
        $pad35 = $reader->readUint8();

        if ($reader->getPosition() !== 0x36)
        {
            throw new \Exception('Error in table implementation!');
        }
        $this->readStringTable($reader, 'name');

        for ($i = 0; $i < $numCompatible; $i++)
        {
            $this->compatibleTracksRoads[] = DATHeader::fromReader($reader);
        }

        for ($i = 0; $i < $numMods; $i++)
        {
            $this->mods[] = DATHeader::fromReader($reader);
        }

        for ($i = 0; $i < $numSignals; $i++)
        {
            $this->signals[] = DATHeader::fromReader($reader);
        }

        $this->tunnel = DATHeader::fromReader($reader);

        for ($i = 0; $i < $numBridges; $i++)
        {
            $this->bridges[] = DATHeader::fromReader($reader);
        }

        for ($i = 0; $i < $numStations; $i++)
        {
            $this->stations[] = DATHeader::fromReader($reader);
        }

        $imageTable = $reader->readBytes(strlen($decoded) - $reader->getPosition());
        $this->imageTable = new ImageTable($imageTable);
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
