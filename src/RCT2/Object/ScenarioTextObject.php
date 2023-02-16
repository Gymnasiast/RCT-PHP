<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Binary;
use RCTPHP\RCT2String;
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
    /** @var RCT2String[][] */
    public array $stringTable = [];

    public readonly bool $isSixFlags;

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x6, SEEK_CUR);
        $this->isSixFlags = (bool)Binary::readUint8($fp);
        fseek($fp, 0x1, SEEK_CUR);

        $this->readStringTable($fp, 0);
        $this->readStringTable($fp, 1);
        $this->readStringTable($fp, 2);

        fclose($fp);
    }

    public function printData(): void
    {
        $isSixFlags = $this->isSixFlags ? 'yes' : 'no';
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Six Flags park: {$isSixFlags}");

        $this->printStringTables();
    }
}