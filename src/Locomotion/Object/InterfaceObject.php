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

class InterfaceObject implements LocomotionObject, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];
    public readonly ImageTable $imageTable;

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);
        $reader->seek(0x18);

        $this->readStringTable($reader, 'name');

        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));
        $this->imageTable->exportToFile('imagetable-g0.dat');
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
