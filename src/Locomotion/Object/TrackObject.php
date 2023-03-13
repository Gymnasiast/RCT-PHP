<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\Binary;
use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\SawyerString;
use RCTPHP\Util;
use function fclose;
use function fopen;
use function fread;
use function fseek;
use function ftell;
use function fwrite;
use function rewind;
use const SEEK_CUR;

class TrackObject implements DATObject, StringTableOwner, ImageTableOwner
{
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


    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x02, SEEK_CUR); // Space for the name;
        $trackPieces = Binary::readUint16($fp);
        $stationTrackPieces = Binary::readUint16($fp);
        $var06 = Binary::readUint8($fp);
        $numCompatible = Binary::readUint8($fp);
        $numMods = Binary::readUint8($fp);
        $numSignals = Binary::readUint8($fp);
        $mods = [];
        for ($i = 0; $i < 4; $i++)
        {
            $mods[$i] = Binary::readUint8($fp);
        }
        $signals = Binary::readUint16($fp);
        $compatibleTracks = Binary::readUint16($fp);
        $compatibleRoads = Binary::readUint16($fp);
        $buildCostFactor = Binary::readSint16($fp);
        $sellCostFactor = Binary::readSint16($fp);
        $tunnelCostFactor = Binary::readSint16($fp);
        $costIndex = Binary::readUint8($fp);
        $tunnel = Binary::readUint8($fp);
        $curveSpeed = Binary::readUint16($fp);
        $image = Binary::readUint32($fp);
        $trackObjectFlags = Binary::readUint16($fp);
        $numBridges = Binary::readUint8($fp);
        $bridges = [];
        for ($i = 0; $i < 7; $i++)
        {
            $bridges[$i] = Binary::readUint8($fp);
        }
        $numStations = Binary::readUint8($fp);
        $stations = [];
        for ($i =0; $i < 7; $i++)
        {
            $stations[$i] = Binary::readUint8($fp);
        }
        $displayOffset = Binary::readUint8($fp);
        $pad35 = Binary::readUint8($fp);

        if (ftell($fp) !== 0x36)
        {
            throw new \Exception('Error in table implementation!');
        }
        $this->readStringTable($fp, 'name');

        for ($i = 0; $i < $numCompatible; $i++)
        {
            $this->compatibleTracksRoads[] = new DATHeader($fp);
        }

        for ($i = 0; $i < $numMods; $i++)
        {
            $this->mods[] = new DATHeader($fp);
        }

        for ($i = 0; $i < $numSignals; $i++)
        {
            $this->signals[] = new DATHeader($fp);
        }

        $this->tunnel = new DATHeader($fp);

        for ($i = 0; $i < $numBridges; $i++)
        {
            $this->bridges[] = new DATHeader($fp);
        }

        for ($i = 0; $i < $numStations; $i++)
        {
            $this->stations[] = new DATHeader($fp);
        }

        $imageTable = fread($fp, strlen($decoded) - ftell($fp));
        $this->imageTable = new ImageTable($imageTable);

        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");

        $this->printStringTables();

        $lists = [
            'Compatible tracks/roads' => &$this->compatibleTracksRoads,
            'Mods' => &$this->mods,
            'Signals' => &$this->signals,
            'Bridges' => &$this->bridges,
            'Stations' => &$this->stations,
        ];
        foreach ($lists as $key => $list)
        {
            $imploded = implode(', ', array_map(static function(DATHeader $header) { return $header->name; }, $list));
            Util::printLn("$key: {$imploded}");
        }

        Util::printLn('Tunnel: ' . ($this->tunnel ? $this->tunnel->name : 'N/A'));
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
