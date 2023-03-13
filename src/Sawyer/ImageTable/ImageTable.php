<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\ImageTable;

use RCTPHP\Binary;
use RCTPHP\Util\RGB;
use function array_fill;
use function file_put_contents;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function imagecolorallocate;
use function imagecreate;
use function imagepng;
use function imagesetpixel;
use function rewind;
use function substr;

require_once __DIR__ . '/../../RCT1/TP4/Palette.php';

final class ImageTable
{
    /** @var ImageHeader[] */
    public readonly array $entries;
    /** @var string[] */
    public readonly array $binaryImageData;

    public readonly array $paletteParts;

    public function __construct(public readonly string $binaryData)
    {
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $binaryData);
        rewind($fp);
        $this->readImageTable($fp);
    }

    public function readImageTable($fp)
    {
        $numImages = Binary::readUint32($fp);
        $imageDataSize = Binary::readUint32($fp);
        $paletteParts = [];

        /** @var ImageHeader[] $entries */
        $entries = [];

        for ($i = 0; $i < $numImages; $i++)
        {
            $entries[] = self::readImageHeader($fp);
        }

        $this->entries = $entries;

        $imageData = fread($fp, $imageDataSize);

        $binaryImageData = [];
        for ($i = 0; $i < $numImages; $i++)
        {
            $currentEntry = $entries[$i];

            $start = $currentEntry->startAddress;
            $end = ($i === $numImages - 1) ? $imageDataSize : $entries[$i + 1]->startAddress;
            $size = $end - $start;

            $dataForThisImage = substr($imageData, $start, $size);
            if (!($currentEntry->flags & ImageHeader::FLAG_PALETTE))
            {
                if ($currentEntry->flags & ImageHeader::FLAG_RLE_COMPRESSION)
                {
                    $decoded = $this->decodeImageRLE($currentEntry, $dataForThisImage);
                }
                else
                {
                    $decoded = $this->readImage($currentEntry, $dataForThisImage);
                }

                $image = imagecreate($currentEntry->width, $currentEntry->height);
                // FIXME: Use a proper palette!
                foreach (\RCTPHP\RCT1\TP4\PALETTE as $index => $color)
                {
                    $id = imagecolorallocate($image, $color->r, $color->g, $color->b);
                    if ($id !== $index)
                    {
                        throw new \Exception("Incorrect index for color {$index}!");
                    }
                }

                for ($y = 0; $y < $currentEntry->height; $y++)
                {
                    for ($x = 0; $x < $currentEntry->width; $x++)
                    {
                        $index = $decoded->getPixel($x, $y);
                        imagesetpixel($image, $x, $y, $index);
                    }
                }

                imagepng($image, "rledecoded-{$i}.png");
            }
            else
            {
                file_put_contents("palette-{$i}.bin", $dataForThisImage);
                $numColors = $currentEntry->width;
                $index = $currentEntry->xOffset;
                $colors = [];
                for ($j = 0; $j < $numColors; $j++)
                {
                    $b = ord($dataForThisImage[($j * 3) + 0]);
                    $g = ord($dataForThisImage[($j * 3) + 1]);
                    $r = ord($dataForThisImage[($j * 3) + 2]);

                    $colors[] = new RGB($r, $g, $b);
                }

                $paletteParts[] = new Palette($index, $numColors, $colors);
            }

            $binaryImageData[] = $dataForThisImage;
        }

        $this->binaryImageData = $binaryImageData;
        $this->paletteParts = $paletteParts;
    }

    private function readImage(ImageHeader $entry, $dataForThisImage): PalettizedImage
    {
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $dataForThisImage);
        rewind($fp);

        $paletteImage = new PalettizedImage($entry->width, $entry->height);
        for ($y = 0; $y < $entry->height; $y++)
        {
            for ($x = 0; $x < $entry->width; $x++)
            {
                $paletteImage->setPixel($x, $y, Binary::readUint8($fp));
            }
        }

        return $paletteImage;
    }

    private function decodeImageRLE(ImageHeader $entry, $dataForThisImage): PalettizedImage
    {
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $dataForThisImage);
        rewind($fp);
        $paletteImage = new PalettizedImage($entry->width, $entry->height);
        $rowOffsets = array_fill(0, $entry->height, 0);

        // Read the row offsets
        for ($j = 0; $j < $entry->height; $j++) {
            $rowOffsets[$j] = Binary::readUint16($fp);
        }

        // Read the scan lines in each row
        for ($j = 0; $j < $entry->height; $j++) {
            fseek($fp, $rowOffsets[$j]);
            $b1 = 0;
            $b2 = 0;

            // An MSB of 1 means the last scan line in a row
            while (($b1 & 0x80) === 0) {
                // Read the number of bytes of data
                $b1 = Binary::readUint8($fp);
                // Read the offset from the left edge of the image
                $b2 = Binary::readUint8($fp);
                for ($k = 0; $k < ($b1 & 0x7F); $k++)
                {
                    $b3 = Binary::readUint8($fp);
                    $x = $b2 + $k;
                    $y = $j;
                    $paletteImage->setPixel($x, $y, $b3);
                }
            }
        }

        return $paletteImage;
    }

    /**
     * @param resource $fp
     * @return ImageHeader
     */
    public static function readImageHeader(&$fp): ImageHeader
    {
        $header = new ImageHeader();
        $header->startAddress = Binary::readUint32($fp);
        $header->width = Binary::readUint16($fp);
        $header->height = Binary::readUint16($fp);
        $header->xOffset = Binary::readSint16($fp);
        $header->yOffset = Binary::readSint16($fp);
        $header->flags = Binary::readUint16($fp);
        $header->zoomedOffset = Binary::readSint16($fp);
        return $header;
    }

    public function exportToFile(string $filename): void
    {
        file_put_contents($filename, $this->binaryData);
    }
}
