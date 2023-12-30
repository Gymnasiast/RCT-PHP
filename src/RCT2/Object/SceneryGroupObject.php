<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\OpenRCT2\Object\SceneryGroupObject as OpenRCT2SceneryGroupObject;
use RCTPHP\OpenRCT2\Object\SceneryGroupProperties;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;
use const STR_PAD_LEFT;
use function strlen;
use function dechex;
use function str_pad;
use function array_map;

class SceneryGroupObject implements RCT2Object, StringTableOwner, ImageTableOwner, ObjectWithOpenRCT2Counterpart
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
            $object = DATHeader::tryFromReader($reader);
            if ($object !== null)
            {
                $this->objects[] = $object;
            }
        }
    }

    /**
     * @return string[]
     */
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
        $entries = array_map(static function(DATHeader $header)
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
