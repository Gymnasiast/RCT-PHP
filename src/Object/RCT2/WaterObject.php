<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\Object\OpenRCT2\WaterObject as OpenRCT2WaterObject;
use RCTPHP\Object\OpenRCT2\WaterProperties;
use RCTPHP\Object\OpenRCT2\WaterPropertiesPalettes;
use RCTPHP\RCT2String;
use RCTPHP\Util;
use RuntimeException;
use function fclose;
use function filesize;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function rewind;
use const SEEK_CUR;

class WaterObject implements DATObject, StringTableOwner, ObjectWithOpenRCT2Counterpart
{
    use StringTableDecoder;

    public bool $allowDucks = true;

    public DatHeader $header;
    /** @var RCT2String[] */
    public array $stringTable = [];

    public function __construct(string $filename)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }

        $this->header = DatHeader::fromStream($fp);
        $restLength = filesize($filename) - 16;
        $rest = fread($fp, $restLength);
        fclose($fp);

        $rledecoded = Util::decodeRLE($rest);

        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $rledecoded);

        rewind($fp);
        fseek($fp, 14, SEEK_CUR);
        $this->allowDucks = (bool)Bytes::readUint16($fp);

        $this->readStringTable($fp);
        // imagetable!

        fclose($fp);
    }

    public function printData(): void
    {
        $allowDucks = $this->allowDucks ? 'true' : 'false';
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Allow ducks: {$allowDucks}");

        foreach ($this->stringTable as $stringTableItem)
        {
            Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
        }
    }

    public function toOpenRCT2Object(): OpenRCT2WaterObject
    {
        $ret = new OpenRCT2WaterObject();
        $ret->properties = new WaterProperties(
            $this->allowDucks,
            new WaterPropertiesPalettes(),
        );

        return $ret;
    }
}
