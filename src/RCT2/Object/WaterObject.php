<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Binary;
use RCTPHP\OpenRCT2\Object\WaterObject as OpenRCT2WaterObject;
use RCTPHP\OpenRCT2\Object\WaterProperties;
use RCTPHP\OpenRCT2\Object\WaterPropertiesPalettes;
use RCTPHP\RCT2String;
use RCTPHP\Util;
use function fclose;
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

    public DATHeader $header;
    /** @var RCT2String[][] */
    public array $stringTable = [];

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 14, SEEK_CUR);
        $this->allowDucks = (bool)Binary::readUint16($fp);

        $this->readStringTable($fp);
        // imagetable!

        fclose($fp);
    }

    public function printData(): void
    {
        $allowDucks = $this->allowDucks ? 'true' : 'false';
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Allow ducks: {$allowDucks}");

        $this->printStringTables();
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
