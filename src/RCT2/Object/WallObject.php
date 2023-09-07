<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\SawyerPrice;
use RCTPHP\Sawyer\SawyerTileHeight;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;
use function strlen;

class WallObject implements RCT2Object, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;

    /** @var StringTable[] */
    public array $stringTable = [];

    public DATHeader|null $attachTo;

    public readonly ImageTable $imageTable;
    public readonly int $toolId;
    public readonly int $flags;
    public readonly SawyerTileHeight $height;
    public readonly int $flags2;
    public readonly SawyerPrice $price;
    public readonly int $scrollingMode;

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(0x6);

        $this->toolId = $reader->readUint8();
        $this->flags = $reader->readUint8();
        $this->height = new SawyerTileHeight($reader->readUint8());
        $this->flags2 = $reader->readUint8();
        $this->price = new SawyerPrice($reader->readUint16());
        $reader->seek(0x1);
        $this->scrollingMode = $reader->readUint8();

        $this->readStringTable($reader, 'name');

        $this->attachTo = DATHeader::try($reader);

        $imageTableSize = strlen($decoded) - $reader->getPosition();
        $imageTable = $reader->readBytes($imageTableSize);
        $this->imageTable = new ImageTable($imageTable);
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
