<?php
namespace RCTPHP;

use RCTPHP\Locomotion\Object\DATDetector as LocoDATDetector;
use RCTPHP\OpenRCT2\Object\ObjectSerializer;
use RCTPHP\RCT2\Object\DATDetector as RCT2DATDetector;
use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\ObjectWithOpenRCT2Counterpart;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use Cyndaron\BinaryHandler\BinaryReader;
use function file_put_contents;
use function var_dump;

class DatDataPrinter
{
    private readonly DATObject|null $object;

    public function __construct(string $filename, bool $isLocomotion, private readonly bool $isDebug = false)
    {
        $reader = BinaryReader::fromFile($filename);

        if ($isLocomotion)
            $detector = new LocoDATDetector($reader);
        else
            $detector = new RCT2DATDetector($reader);

        $this->object = $detector->getObject();
    }

    public function printData(): void
    {
        if ($this->object === null)
        {
            Util::printLn('Onbekend type!');
            return;
        }

        $this->object->printData();
        if ($this->isDebug && $this->object instanceof ImageTableOwner)
        {
            $imageTable = $this->object->getImageTable();
            foreach ($imageTable->entries as $entry)
            {
                var_dump($entry);
            }
        }

        if ($this->object instanceof ObjectWithOpenRCT2Counterpart)
        {
            $converted = $this->object->toOpenRCT2Object();
            $serialized = (new ObjectSerializer($converted))->serializeToJson();
            file_put_contents('converted.json', $serialized);
        }
    }
}
