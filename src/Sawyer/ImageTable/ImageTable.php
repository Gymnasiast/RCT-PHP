<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\ImageTable;

use RCTPHP\Util\RGB;
use Cyndaron\BinaryHandler\BinaryReader;
use function array_fill;
use function file_put_contents;
use function imagecolorallocate;
use function imagecreate;
use function imagepng;
use function imagesetpixel;
use function substr;
use function ord;
use function assert;

require_once __DIR__ . '/../../RCT1/TP4/Palette.php';

final class ImageTable
{
    /** @var ImageHeader[] */
    public readonly array $entries;
    /** @var string[] */
    public readonly array $binaryImageData;

    /** @var Palette[] */
    public readonly array $paletteParts;

    public function __construct(public readonly string $binaryData)
    {
        $reader = BinaryReader::fromString($this->binaryData);

        $numImages = $reader->readUint32();
        $imageDataSize = $reader->readUint32();
        $paletteParts = [];

        /** @var ImageHeader[] $entries */
        $entries = [];

        for ($i = 0; $i < $numImages; $i++)
        {
            $entries[] = self::readImageHeader($reader);
        }

        $this->entries = $entries;

        $imageData = $reader->readBytes($imageDataSize);

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
                assert($image !== false);
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
            }
            else
            {
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

    private function readImage(ImageHeader $entry, string $dataForThisImage): PalettizedImage
    {
        $reader = BinaryReader::fromString($dataForThisImage);
        $paletteImage = new PalettizedImage($entry->width, $entry->height);
        for ($y = 0; $y < $entry->height; $y++)
        {
            for ($x = 0; $x < $entry->width; $x++)
            {
                $paletteImage->setPixel($x, $y, $reader->readUint8());
            }
        }

        return $paletteImage;
    }

    private function decodeImageRLE(ImageHeader $entry, string $dataForThisImage): PalettizedImage
    {
        $reader = BinaryReader::fromString($dataForThisImage);
        $paletteImage = new PalettizedImage($entry->width, $entry->height);
        $rowOffsets = array_fill(0, $entry->height, 0);

        // Read the row offsets
        for ($j = 0; $j < $entry->height; $j++)
        {
            $rowOffsets[$j] = $reader->readUint16();
        }

        // Read the scan lines in each row
        for ($j = 0; $j < $entry->height; $j++)
        {
            $reader->moveTo($rowOffsets[$j]);
            $b1 = 0;
            $b2 = 0;

            // An MSB of 1 means the last scan line in a row
            while (($b1 & 0x80) === 0)
            {
                // Read the number of bytes of data
                $b1 = $reader->readUint8();
                // Read the offset from the left edge of the image
                $b2 = $reader->readUint8();
                for ($k = 0; $k < ($b1 & 0x7F); $k++)
                {
                    $b3 = $reader->readUint8();
                    $x = $b2 + $k;
                    $y = $j;
                    $paletteImage->setPixel($x, $y, $b3);
                }
            }
        }

        return $paletteImage;
    }

    /**
     * @param BinaryReader $reader
     * @return ImageHeader
     */
    public static function readImageHeader(BinaryReader $reader): ImageHeader
    {
        $header = new ImageHeader();
        $header->startAddress = $reader->readUint32();
        $header->width = $reader->readUint16();
        $header->height = $reader->readUint16();
        $header->xOffset = $reader->readSint16();
        $header->yOffset = $reader->readSint16();
        $header->flags = $reader->readUint16();
        $header->zoomedOffset = $reader->readSint16();
        return $header;
    }

    public function exportToFile(string $filename): void
    {
        file_put_contents($filename, $this->binaryData);
    }
}
