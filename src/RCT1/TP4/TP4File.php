<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\TP4;

use GdImage;
use function chr;
use function fwrite;
use function getimagesize;
use function imagecolorat;
use function imagepalettetotruecolor;
use function imagesx;
use function imagesy;
use function var_dump;

//require __DIR__ . '/Palette.php';

final class TP4File
{
    public const WIDTH = 254;
    public const HEIGHT = 200;

    public function __construct(GdImage $image, private bool $keepPalette)
    {
        $this->image = $image;
        if (!$this->keepPalette)
        {
            $success = imagepalettetotruecolor($this->image);
            if (!$success)
            {
                throw new \RuntimeException('Could not convert to true color!');
            }
        }
    }

    public function writeTP4(string $filename)
    {
        $fp = fopen($filename, 'wb');
        fwrite($fp, $this->generateHeader());
        for ($lineNum = 0; $lineNum < self::HEIGHT; $lineNum++)
        {
            fwrite($fp, chr(0x7F));
            fwrite($fp, chr(0x00));

            for ($i = 0; $i < 127; $i++)
            {
                $color = imagecolorat($this->image, $i, $lineNum);
                $index = $this->getPaletteIndex($color);
                fwrite($fp, chr($index));
            }

            fwrite($fp, chr(0xFF));
            fwrite($fp, chr(0x7F));

            for ($i = 127; $i < 254; $i++)
            {
                $color = imagecolorat($this->image, $i, $lineNum);
                $index = $this->getPaletteIndex($color);
                fwrite($fp, chr($index));
            }
        }
    }

    public function getPaletteIndex(int $colorNumber)
    {
        if ($this->keepPalette)
        {
            return $colorNumber;
        }

        $colors = imagecolorsforindex($this->image, $colorNumber);
        foreach (PALETTE as $index => $paletteColour)
        {
            if ($paletteColour->r == $colors['red'] && $paletteColour->g == $colors['green'] && $paletteColour->b == $colors['blue'])
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
}
