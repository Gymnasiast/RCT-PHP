<?php
namespace RCTPHP;

use RCTPHP\Object\DatHeader;
use RCTPHP\Object\SceneryGroupObject;

class DatDataPrinter
{
    private int $type;
    private string $filename;

    public function __construct(string $filename)
    {
        $header = new DatHeader($filename);
        $this->filename = $filename;
        $this->type = $header->getType();
    }

    public function printData()
    {
        if ($this->type === DatHeader::OBJECT_TYPE_SCENERY_GROUP)
        {
            $object = new SceneryGroupObject($this->filename);
            $object->printData();
        }
    }
}
