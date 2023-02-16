<?php
namespace RCTPHP;

use RCTPHP\Object\Locomotion\TrackObject;
use RCTPHP\RCT2\Object\DatHeader;
use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\GenericObject;
use RCTPHP\RCT2\Object\SceneryGroupObject;
use RCTPHP\RCT2\Object\WallObject;
use RCTPHP\RCT2\Object\WaterObject;
use RuntimeException;
use function fopen;

class DatDataPrinter
{
    private int $type;
    private string $filename;
    private ?DATObject $object = null;

    public function __construct(string $filename)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }
        $header = new DatHeader($fp);
        fclose($fp);
        $this->filename = $filename;
        $this->type = $header->getType();
        $this->read();
    }

    private function read(): void
    {
        $fp = fopen($this->filename, 'rb');
        $filesize = fstat($fp)['size'];

        switch ($this->type)
        {
            case DatHeader::OBJECT_TYPE_WALLS:
                $this->object = new WallObject($fp, $filesize);
                break;
            case DatHeader::OBJECT_TYPE_SCENERY_GROUP:
                $this->object = new SceneryGroupObject($this->filename);
                return;
            case DatHeader::OBJECT_TYPE_WATER:
                $this->object = new WaterObject($this->filename);
                return;
            case DatHeader::LOCOMOTION_TRACK:
                $this->object = new TrackObject($this->filename);
                break;
            default:
                $this->object = new GenericObject($this->filename);
        }
    }

    public function printData(): void
    {
        if ($this->object === null)
        {
            Util::printLn('Onbekend type!');
            return;
        }

        $this->object->printData();
    }
}
