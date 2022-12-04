<?php
namespace RCTPHP;

use RCTPHP\Object\RCT2\DatHeader;
use RCTPHP\Object\RCT2\DATObject;
use RCTPHP\Object\RCT2\GenericObject;
use RCTPHP\Object\RCT2\SceneryGroupObject;
use RCTPHP\Object\RCT2\WaterObject;

class DatDataPrinter
{
    private int $type;
    private string $filename;
    private ?DATObject $object = null;

    public function __construct(string $filename)
    {
        $header = new DatHeader($filename);
        $this->filename = $filename;
        $this->type = $header->getType();
        $this->read();
    }

    private function read(): void
    {
        switch ($this->type)
        {
            case DatHeader::OBJECT_TYPE_SCENERY_GROUP:
                $this->object = new SceneryGroupObject($this->filename);
                return;
            case DatHeader::OBJECT_TYPE_WATER:
                $this->object = new WaterObject($this->filename);
                return;
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
