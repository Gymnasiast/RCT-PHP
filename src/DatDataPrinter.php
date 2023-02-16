<?php
namespace RCTPHP;

use RCTPHP\Locomotion\Object\DATHeader as LocoDATHeader;
use RCTPHP\Locomotion\Object\TrackObject;
use RCTPHP\RCT2\Object\DATHeader as RCT2DATHeader;
use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\SceneryGroupObject;
use RCTPHP\RCT2\Object\WallObject;
use RCTPHP\RCT2\Object\WaterObject;
use RCTPHP\Sawyer\Object\DATHeader;
use RCTPHP\Sawyer\Object\GenericObject;
use RuntimeException;
use function array_key_exists;
use function fopen;

class DatDataPrinter
{
    private string $filename;
    private DATHeader $header;
    private ?DATObject $object = null;

    private const OBJECT_MAPPING_RCT2 = [
        RCT2DATHeader::OBJECT_TYPE_WALLS => WallObject::class,
        RCT2DATHeader::OBJECT_TYPE_SCENERY_GROUP => SceneryGroupObject::class,
        RCT2DATHeader::OBJECT_TYPE_WATER => WaterObject::class,
    ];

    private const OBJECT_MAPPING_LOCOMOTION = [
        LocoDATHeader::OBJECT_TYPE_TRACK => TrackObject::class,
    ];

    public function __construct(string $filename, bool $isLocomotion)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }

        if ($isLocomotion)
            $this->header = new LocoDATHeader($fp);
        else
            $this->header = new RCT2DATHeader($fp);

        fclose($fp);
        $this->filename = $filename;
        $this->read();
    }

    private function read(): void
    {
        $fp = fopen($this->filename, 'rb');
        $filesize = fstat($fp)['size'];
        $type = $this->header->getType();
        $class = GenericObject::class;

        if ($this->header instanceof LocoDATHeader)
        {
            if (array_key_exists($type, self::OBJECT_MAPPING_LOCOMOTION))
            {
                $class = self::OBJECT_MAPPING_LOCOMOTION[$type];
            }
        }
        else
        {
            if (array_key_exists($type, self::OBJECT_MAPPING_RCT2))
            {
                $class = self::OBJECT_MAPPING_RCT2[$type];
            }
        }

        $this->object = new $class($this->header, $fp, $filesize);
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
