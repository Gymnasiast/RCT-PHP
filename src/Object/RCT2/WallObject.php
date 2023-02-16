<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\Binary;
use RCTPHP\RCT2String;
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

class WallObject implements DATObject, StringTableOwner
{
    use StringTableDecoder;

    public DatHeader $header;



    /** @var RCT2String[] */
    public array $stringTable = [];

    public DatHeader|null $attachTo;

    // Binary
    public readonly string $imageTable;
    public readonly int $toolId;
    public readonly int $flags;
    public readonly SawyerTileHeight $height;
    public readonly int $flags2;
    public readonly SawyerPrice $price;
    public readonly int $scrollingMode;

    /**
     * @param resource $fp
     */
    public function __construct($fp, int $filesize)
    {
        $this->header = DatHeader::fromStream($fp);
        $restLength = $filesize - 16;
        $rest = fread($fp, $restLength);
        fclose($fp);

        $rledecoded = Util::decodeRLE($rest);

        $fp = fopen('php://memory', 'rwb+');
        file_put_contents('rledecoded', $rledecoded);
        fwrite($fp, $rledecoded);

        rewind($fp);

        fseek($fp, 0x6, SEEK_CUR);

        // Again 3???
        fseek($fp, 0x3, SEEK_CUR);

        $this->toolId = Binary::readUint8($fp);
        $this->flags = Binary::readUint8($fp);
        $this->height = new SawyerTileHeight(Binary::readUint8($fp));
        $this->flags2 = Binary::readUint8($fp);
        $this->price = new SawyerPrice(Binary::readUint16($fp));
        fseek($fp, 0x1, SEEK_CUR);
        $this->scrollingMode = Binary::readUint8($fp);

        $this->readStringTable($fp);

        $this->attachTo = DatHeader::try($fp);

        $imageTableSize = strlen($rledecoded) - ftell($fp);
        $this->imageTable = fread($fp, $imageTableSize);

        file_put_contents('imagetable-g0.dat', $this->imageTable);

        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Height: {$this->height} units ({$this->height->asMetres()})");
        Util::printLn("Price: {$this->price->asGBP()}");

        foreach ($this->stringTable as $stringTableItem)
        {
            Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
        }
    }

}
