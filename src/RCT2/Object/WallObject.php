<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\SawyerPrice;
use RCTPHP\Sawyer\SawyerTileHeight;
use RCTPHP\Util;
use TXweb\BinaryHandler\BinaryReader;

class WallObject implements DATObject, StringTableOwner, ImageTableOwner
{
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

    public function __construct($header, string $decoded)
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

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Height: {$this->height} units ({$this->height->asMetres()})");
        Util::printLn("Price: {$this->price->asGBP()}");

        $this->printStringTables();
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }
}
