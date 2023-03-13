<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\SawyerString;
use RCTPHP\Util;
use function fclose;
use function fopen;
use function fseek;
use function fwrite;
use function rewind;
use const SEEK_CUR;

class ScenarioTextObject implements DATObject, StringTableOwner
{
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x6, SEEK_CUR);

        $this->readStringTable($fp, 'name');
        $this->readStringTable($fp, 'description');

        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");

        $this->printStringTables();
    }
}
