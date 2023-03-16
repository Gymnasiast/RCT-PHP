<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Util;
use TXweb\BinaryHandler\BinaryReader;

class ScenarioTextObject implements DATObject, StringTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);
        $reader->seek(0x6);

        $this->readStringTable($reader, 'name');
        $this->readStringTable($reader, 'description');
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");

        $this->printStringTables();
    }
}
