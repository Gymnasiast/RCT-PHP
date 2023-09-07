<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;

class ScenarioTextObject implements RCT2Object, StringTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];

    public readonly bool $isSixFlags;

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(0x6);
        $this->isSixFlags = (bool)$reader->readUint8();
        $reader->seek(0x1);

        $this->readStringTable($reader, 'scenario_name');
        $this->readStringTable($reader, 'park_name');
        $this->readStringTable($reader, 'description');
    }
}
