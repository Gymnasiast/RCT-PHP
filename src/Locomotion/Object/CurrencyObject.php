<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Util;
use TXweb\BinaryHandler\BinaryReader;

class CurrencyObject implements DATObject, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];
    public readonly ImageTable $imageTable;

    public function __construct($header, string $decoded)
    {
        $this->header = $header;

        $reader = BinaryReader::fromString($decoded);
        $reader->seek(0x0C);

        $this->readStringTable($reader, 'name');
        $this->readStringTable($reader, 'prefix');
        $this->readStringTable($reader, 'suffix');

        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));
        $this->imageTable->exportToFile('imagetable-g0.dat');
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");

        $this->printStringTables();
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
