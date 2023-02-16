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
    /** @var RCT2String[] */
    public array $stringTable = [];

    public function __construct($header, $fp, $filesize)
    {
        $this->header = $header;
        fseek($fp, DATHeader::DAT_HEADER_SIZE);
        $restLength = $filesize - DATHeader::DAT_HEADER_SIZE;
        $rest = fread($fp, $restLength);
        fclose($fp);

        $rledecoded = Util::decodeRLE($rest);

        $fp = fopen('php://memory', 'rwb+');
        //file_put_contents('rledecoded', $rledecoded);
        fwrite($fp, $rledecoded);

        rewind($fp);
        fseek($fp, 0x36, SEEK_CUR);
        // WHY???
        fseek($fp, 0x3, SEEK_CUR);

        $this->readStringTable($fp);

        fseek($fp, 0x2A0, SEEK_SET);
        $imageTable = fread($fp, $restLength - 0x2A0);
        file_put_contents('imagetable-g0.dat', $imageTable);
        // imagetable!

        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");

        foreach ($this->stringTable as $stringTableItem)
        {
            Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
        }
    }
}
