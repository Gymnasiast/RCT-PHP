<?php
declare(strict_types=1);

namespace RCTPHP\Util\PCX;

use Cyndaron\BinaryHandler\BinaryReader;
use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use GdImage;
use RCTPHP\Util\Reader\ReadableInterface;
use RCTPHP\Util\Reader\TryFromReaderTrait;
use RuntimeException;
use function assert;
use function chr;
use function imagecolorallocate;
use function imagecreate;
use function imagesetpixel;
use function strlen;

/**
 * Some inspiration taken from https://formats.kaitai.io/pcx/php.html
 */
final class PCXImage implements ReadableInterface
{
    use TryFromReaderTrait;

    private const NUM_PALETTE_ENTRIES = 256;
    private const PALETTE_DATA_SIZE = self::NUM_PALETTE_ENTRIES * 3;

    /** @var string Image data, after RLE decoding */
    private string $imageData;
    private string $paletteData;

    public function __construct(private readonly PCXHeader $header, private readonly string $rest)
    {
        $restSize = strlen($this->rest);
        $reader = BinaryReader::fromString($this->rest);

        if ($this->has256Palette())
        {
            $imageDataSize = $restSize - self::PALETTE_DATA_SIZE;
            $this->imageData = $this->readImageData($reader, $imageDataSize);
            $this->paletteData = $reader->readBytes(self::PALETTE_DATA_SIZE);
        }
        else
        {
            $imageDataSize = $restSize;
            $this->imageData = $this->readImageData($reader, $imageDataSize);
            $this->paletteData = $this->header->palette16;
        }
    }

    private function getImageDataSize(): int
    {
        return $this->header->getWidth() * $this->header->getHeight();
    }

    private function readImageData(BinaryReader $reader, int $size): string
    {
        if ($this->header->encoding === 1)
        {
            return $this->decodeRLEImageData($reader, $size);
        }

        return $reader->readBytes($this->getImageDataSize());
    }

    private function decodeRLEImageData(BinaryReader $reader, int $size): string
    {
        $imageDataDecoded = '';
        for ($bytesRead = 0; $bytesRead < $size;)
        {
            $byte = $reader->readUint8();
            $bytesRead++;
            $low6 = $byte & 0b00111111;
            $high2 = $byte & 0b11000000;
            $isRunLength = $high2 === 0b11000000;
            if ($isRunLength)
            {
                $runLength = $low6;
                $value = $reader->readUint8();
                $bytesRead++;
                for ($i = 0; $i < $runLength; $i++)
                {
                    $imageDataDecoded .= chr($value);
                }
            }
            else
            {
                $imageDataDecoded .= chr($byte);
            }
        }

        return $imageDataDecoded;
    }

    public function has256Palette(): bool
    {
        return $this->header->bitsPerPixel === 8;
    }

    private function apply256Palette(GdImage $gd): void
    {
        $paletteReader = BinaryReader::fromString($this->paletteData);
        for ($index = 0; $index < self::NUM_PALETTE_ENTRIES; $index++)
        {
            $r = $paletteReader->readUint8();
            $g = $paletteReader->readUint8();
            $b = $paletteReader->readUint8();

            $allocIndex = imagecolorallocate($gd, $r, $g, $b);
            assert($allocIndex === $index);
        }
    }

    public function exportAsGdImage(): GdImage
    {
        $imageWidth = $this->header->getWidth();
        $imageHeight = $this->header->getHeight();

        $gd = imagecreate($imageWidth, $imageHeight);
        if ($gd === false)
        {
            throw new RuntimeException("Could not create image with width {$imageWidth} and height {$imageHeight}!");
        }

        $decodedDataReader = BinaryReader::fromString($this->imageData);
        for ($y = 0; $y < $imageHeight; $y++)
        {
            for ($x = 0; $x < $imageWidth; $x++)
            {
                $byte = $decodedDataReader->readUint8();
                imagesetpixel($gd, $x, $y, $byte);
            }
        }

        $this->apply256Palette($gd);

        return $gd;
    }

    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): self
    {
        $header = PCXHeader::fromReader($reader);
        $restSize = $reader->getSize() - $reader->getPosition();
        $rest = $reader->readBytes($restSize);

        return new self($header, $rest);
    }
}
