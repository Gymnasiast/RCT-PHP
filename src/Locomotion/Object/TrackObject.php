<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\RCT2String;
use RCTPHP\Util;
use function fclose;
use function file_put_contents;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function rewind;
use const SEEK_CUR;

class TrackObject implements DATObject, StringTableOwner
{
    use StringTableDecoder;

    public DATHeader $header;
    /** @var RCT2String[][] */
    public array $stringTable = [];

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x36, SEEK_CUR);

        $this->readStringTable($fp);

        // TODO: properly read the object table!
        fseek($fp, 0x2A0, SEEK_SET);
        $imageTable = fread($fp, strlen($decoded) - 0x2A0);
        file_put_contents('imagetable-g0.dat', $imageTable);
        // imagetable!

        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");

        $this->printStringTables();
    }
}