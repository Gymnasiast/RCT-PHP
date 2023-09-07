<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;

class ScenarioTextObject implements LocomotionObject, StringTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);
        $reader->seek(0x6);

        $this->readStringTable($reader, 'name');
        $this->readStringTable($reader, 'description');
    }
}
