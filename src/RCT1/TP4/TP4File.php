<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\TP4;

use Exception;
use GdImage;
use RuntimeException;
use TXweb\BinaryHandler\BinaryReader;
use TXweb\BinaryHandler\BinaryWriter;
use function chr;
use function imagecolorat;
use function imagepalettetotruecolor;

require_once __DIR__ . '/Palette.php';

final class TP4File
{
    public const WIDTH = 254;
    public const HEIGHT = 200;
    private GdImage $image;

    public function __construct(GdImage $image, private readonly bool $keepPalette)
    {
        $this->image = $image;
        if (!$this->keepPalette)
        {
            $success = imagepalettetotruecolor($this->image);
            if (!$success)
            {
                throw new RuntimeException('Could not convert to true color!');
            }
        }
    }

    public function writeTP4(string $filename): void
    {
        $writer = BinaryWriter::fromFile($filename);
        $writer->writeBytes($this->generateHeader());
        for ($lineNum = 0; $lineNum < self::HEIGHT; $lineNum++)
        {
            $writer->writeBytes(chr(0x7F));
            $writer->writeBytes(chr(0x00));

            for ($i = 0; $i < 127; $i++)
            {
                $color = imagecolorat($this->image, $i, $lineNum);
                $index = $this->getPaletteIndex($color);
                $writer->writeBytes(chr($index));
            }

            $writer->writeBytes(chr(0xFF));
            $writer->writeBytes(chr(0x7F));

            for ($i = 127; $i < 254; $i++)
            {
                $color = imagecolorat($this->image, $i, $lineNum);
                $index = $this->getPaletteIndex($color);
                $writer->writeBytes(chr($index));
            }
        }
    }

    public function getPaletteIndex(int $colorNumber): int
    {
        if ($this->keepPalette)
        {
            return $colorNumber;
        }

        $colors = imagecolorsforindex($this->image, $colorNumber);
        foreach (PALETTE as $index => $paletteColor)
        {
            if ($paletteColor->r === $colors['red'] && $paletteColor->g === $colors['green'] && $paletteColor->b === $colors['blue'])
            {
                return $index;
            }
        }

        return $colorNumber;

    }

    public function generateHeader(): string
    {
        $output = '';
        for($i = 0, $countby2 = 0x90, $countby1 = 1; $i < 200; $i++)
        {
            $output .= chr($countby2);
            $output .= chr($countby1);
            $countby2 = ($countby2 + 2) % 256;
            if ($countby2 === 0)
                $countby1 = ($countby1 + 2) % 256;
            else
                $countby1 = ($countby1 + 1) % 256;
        }

        return $output;
    }

    /**
     * @param BinaryReader $reader
     * @return self
     * @throws Exception
     */
    public static function createFromFile(BinaryReader $reader): self
    {
        $reader->seek(400);

        $image = imagecreate(self::WIDTH, self::HEIGHT);
        foreach (\RCTPHP\RCT1\TP4\PALETTE as $index => $color)
        {
            $id = imagecolorallocate($image, $color->r, $color->g, $color->b);
            if ($id !== $index)
            {
                throw new \Exception("Incorrect index for color {$index}!");
            }
        }


        for ($lineNum = 0; $lineNum < self::HEIGHT; $lineNum++)
        {
            $startFlag = $reader->readUint16();
            for ($i = 0; $i < 127; $i++)
            {
                $index = $reader->readUint8();
                imagesetpixel($image, $i, $lineNum, $index);
            }
            $midFlag = $reader->readUint16();
            for ($i = 0; $i < 127; $i++)
            {
                $index = $reader->readUint8();
                imagesetpixel($image, $i + 127, $lineNum, $index);
            }
        }

        return new self($image, true);
    }

    /**
     * @param string $filename
     * @return void
     * @throws RuntimeException If the file cannot be written.
     */
    public function writeImage(string $filename): void
    {
        $result = imagepng($this->image, $filename);
        if (!$result)
            throw new RuntimeException("Could not write output image to {$filename}!");
    }
}
