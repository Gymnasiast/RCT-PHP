<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\RCT2String;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Util;
use function fclose;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function rewind;
use const SEEK_CUR;

class CurrencyObject implements DATObject, StringTableOwner, ImageTableOwner
{
    use StringTableDecoder;

    public DATHeader $header;
    /** @var RCT2String[][] */
    public array $stringTable = [];
    public readonly ImageTable $imageTable;

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x0C, SEEK_CUR);

        $this->readStringTable($fp, 0);
        $this->readStringTable($fp, 1);
        $this->readStringTable($fp, 2);

        $this->imageTable = new ImageTable(fread($fp, strlen($decoded) - ftell($fp)));
        $this->imageTable->exportToFile('imagetable-g0.dat');

        fclose($fp);
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
