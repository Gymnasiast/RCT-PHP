<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\OpenRCT2\Object\SceneryGroupObject as OpenRCT2SceneryGroupObject;
use RCTPHP\OpenRCT2\Object\SceneryGroupProperties;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;
use const STR_PAD_LEFT;

class SceneryGroupObject implements DATObject, StringTableOwner, ImageTableOwner, ObjectWithOpenRCT2Counterpart
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
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
        $reader = BinaryReader::fromString($decoded);
        $reader->seek(0x10B);
        //        fseek($fp, 6 + (0x80 * 2), SEEK_CUR); // ?
//        fseek($fp, 8, SEEK_CUR);
//        $this->numEntries = ord(fread($fp, 1));
//        fseek($fp, 1, SEEK_CUR);
        $this->priority = $reader->readUint8();

        $reader->seek(1);
        $this->entertainerCostumes = $reader->readUint32() >> 4;

        $this->readStringTable($reader, 'name');

        $this->readObjects($reader);

        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Priority: {$this->priority}");

        foreach ($this->stringTable['name'] as $stringTableItem)
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

    private function readObjects(BinaryReader $reader): void
    {
        while (true)
        {
            $byte = $reader->readUint8();
            if ($byte === 0xFF)
            {
                break;
            }

            $reader->seek(-1);
            $this->objects[] = DATHeader::try($reader);
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
            return $header->getAsSceneryGroupListEntry();
        }, $this->objects);

        $ret = new OpenRCT2SceneryGroupObject();
        $ret->properties = new SceneryGroupProperties(
            $entries,
            $this->priority,
            $this->getEntertainerCostumes(),
        );
        $ret->images = $this->imageTable;

        return $ret;
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
