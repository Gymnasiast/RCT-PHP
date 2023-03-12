<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Binary;
use RCTPHP\RCT2String;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\SawyerPrice;
use RCTPHP\Sawyer\SawyerTileHeight;
use RCTPHP\Util;
use function fclose;
use function file_put_contents;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function rewind;
use const SEEK_CUR;

class WallObject implements DATObject, StringTableOwner, ImageTableOwner
{
    use StringTableDecoder;

    public DATHeader $header;



    /** @var RCT2String[][] */
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
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);

        fseek($fp, 0x6, SEEK_CUR);

        $this->toolId = Binary::readUint8($fp);
        $this->flags = Binary::readUint8($fp);
        $this->height = new SawyerTileHeight(Binary::readUint8($fp));
        $this->flags2 = Binary::readUint8($fp);
        $this->price = new SawyerPrice(Binary::readUint16($fp));
        fseek($fp, 0x1, SEEK_CUR);
        $this->scrollingMode = Binary::readUint8($fp);

        $this->readStringTable($fp);

        $this->attachTo = DATHeader::try($fp);

        $imageTableSize = strlen($decoded) - ftell($fp);
        $imageTable = fread($fp, $imageTableSize);
        $this->imageTable = new ImageTable($imageTable);

        fclose($fp);
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
