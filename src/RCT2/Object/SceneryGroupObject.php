<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\OpenRCT2\Object\SceneryGroupObject as OpenRCT2SceneryGroupObject;
use RCTPHP\OpenRCT2\Object\SceneryGroupProperties;
use RCTPHP\RCT2String;
use RCTPHP\Sawyer\Object\ImageTable;
use RCTPHP\Util;
use function fclose;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function ord;
use function rewind;
use const SEEK_CUR;
use const STR_PAD_LEFT;

class SceneryGroupObject implements DATObject, StringTableOwner, ImageTableOwner, ObjectWithOpenRCT2Counterpart
{
    use StringTableDecoder;

    public DATHeader $header;
    /** @var RCT2String[][] */
    public array $stringTable = [];
    /** @var DATHeader[] */
    public array $objects = [];
    public ImageTable $imageTable;
    public int $numEntries = 0;
    public int $priority;
    // Bitset.
    private int $entertainerCostumes;

    private const ENTERTAINER_CUSTUMES = [
        "panda",
        "tiger",
        "elephant",
        "roman",
        "gorilla",
        "snowman",
        "knight",
        "astronaut",
        "bandit",
        "sheriff",
        "pirate",
    ];

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x10B, SEEK_CUR);
        //        fseek($fp, 6 + (0x80 * 2), SEEK_CUR); // ?
//        fseek($fp, 8, SEEK_CUR);
//        $this->numEntries = ord(fread($fp, 1));
//        fseek($fp, 1, SEEK_CUR);
        $this->priority = ord(fread($fp, 1));

        fseek($fp, 1, SEEK_CUR);
        $this->entertainerCostumes = unpack('V', (fread($fp, 4)))[1] >> 4; // 32-bit little endian

        $this->readStringTable($fp);

        $this->readObjects($fp);

        $this->imageTable = new ImageTable(fread($fp, strlen($rledecoded) - ftell($fp)));
        //$this->readImageTable($fp);


        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Priority: {$this->priority}");

        foreach ($this->stringTable[0] as $stringTableItem)
        {
            //if ($stringTableItem->languageCode === 0)
            {
                Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
            }
        }

        Util::printLn('');
        Util::printLn("Entertainer custumes:");
        foreach ($this->getEntertainerCostumes() as $costume)
        {
            Util::printLn("  {$costume}");
        }

        Util::printLn('');

        Util::printLn('Objects:');
        Util::printLn('');

        foreach ($this->objects as $objectHeader)
        {
            $flags = str_pad(dechex($objectHeader->flags), 8, "0", STR_PAD_LEFT);
            $paddedName = str_pad($objectHeader->name, 8);
            $string = "{$flags}|{$paddedName}";
            Util::printLn($string);
        }

        $this->imageTable->exportToFile('scengroupg0.dat');

        Util::printLn('');
    }

    /**
     * @param resource $fp
     * @return void
     */
    private function readObjects(&$fp): void
    {
        while (true)
        {
            $byte = ord(fread($fp, 1));
            if ($byte === 0xFF)
            {
                break;
            }

            fseek($fp, -1, SEEK_CUR);
            $this->objects[] = DATHeader::try($fp);
        }
    }

    public function getEntertainerCostumes(): array
    {
        $ret = [];
        foreach (self::ENTERTAINER_CUSTUMES as $index => $custume)
        {
            if ($this->entertainerCostumes & (1 << $index))
            {
                $ret[] = $custume;
            }
        }

        return $ret;
    }

    public function toOpenRCT2Object(): OpenRCT2SceneryGroupObject
    {
        $entries = array_map(static function (DATHeader $header)
        {
            return $header->toOpenRCT2SceneryGroupNotation();
        }, $this->objects);

        $ret = new OpenRCT2SceneryGroupObject();
        $ret->properties = new SceneryGroupProperties(
            $entries,
            $this->priority,
            $this->getEntertainerCostumes(),
        );
        // TODO: images

        return $ret;
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
