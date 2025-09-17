<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

use GdImage;
use RCTPHP\OpenRCT2\Object\WaterObject;
use RCTPHP\OpenRCT2\Object\WaterPaletteGroup;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Util\RGB;

final class ImageHelper
{
    public static function allocatePalettedImage(int $width, int $height): GdImage
    {
        $image = imagecreate($width, $height);
        assert($image !== false);
        // FIXME: Use a proper palette!
        foreach (\RCTPHP\RCT1\TrackDesign\PALETTE as $index => $color)
        {
            if ($index == 226)
            {
                $color = new RGB(0x37, 0x4b, 0x4b);
            }
            $id = imagecolorallocate($image, $color->r, $color->g, $color->b);
            if ($id !== $index)
            {
                throw new \Exception("Incorrect index for color {$index}!");
            }
        }

        imagecolortransparent($image, 0);

        return $image;
    }

    public static function copyImage(GdImage $src, GdImage $dst, int $destX, int $destY)
    {
        $srcWidth = imagesx($src);
        $srcHeight = imagesy($src);

        // Needed to copy the palette index.
        for ($y = 0; $y < $srcHeight; $y++)
        {
            for ($x = 0; $x < $srcWidth; $x++)
            {
                $colorIndex = imagecolorat($src, $x, $y);
                if ($colorIndex !== 0)
                {
                    imagesetpixel($dst, $destX + $x, $destY + $y, $colorIndex);
                }
            }
        }

        //imagecopy($dst, $src, $destX, $destY, 0, 0, $srcWidth, $srcHeight);
    }

    public static function copyImageTableEntry(ImageTable $imageTable, int $index, GdImage $dst, int $destX, int $destY): void
    {
        $src = $imageTable->gdImageData[$index];
        $meta = $imageTable->entries[$index];

        self::copyImage($src, $dst, $destX + $meta->xOffset, $destY + $meta->yOffset);
    }

    public static function applyPalette(GdImage $image, WaterObject $object): void
    {
        $parts = $object->properties->palettes->getParts();
        $colorsWaves = $parts[WaterPaletteGroup::WAVES_0->value]->colors;
        $colorsSparkles = $parts[WaterPaletteGroup::SPARKLES_0->value]->colors;

        $group = $parts[WaterPaletteGroup::GENERAL->value];

        $offset = $group->index;
        for ($index = 0; $index < $group->numColors; $index++)
        {
            $rgb = $group->colors[$index];
            imagecolorset($image, $index + $offset, $rgb->r, $rgb->g, $rgb->b);
        }

        $currentFrame = 0;
        for ($j = 0; $j < 5; $j++)
        {
            $actualFrame = WaterObject::NUM_ANIMATED_WATER_FRAMES - $currentFrame;
            $subIndex = ($actualFrame + (3 * $j)) % 15;
            $rgb = $colorsWaves[$subIndex];
            imagecolorset($image, WaterObject::WAVE_START + $j, $rgb->r, $rgb->g, $rgb->b);
            $rgb = $colorsSparkles[$subIndex];
            imagecolorset($image, WaterObject::SPARKLE_START + $j, $rgb->r, $rgb->g, $rgb->b);
        }
    }

    private static function setRemap(GdImage $image, int $remapStart, int $colorIndexStart)
    {
        for ($offset = 0; $offset < 12; $offset++)
        {
            $newColorInfo = imagecolorsforindex($image, $colorIndexStart + $offset);
            imagecolorset($image, $remapStart + $offset, $newColorInfo['red'], $newColorInfo['green'], $newColorInfo['blue']);
        }
    }

    public static function setPrimaryRemap(GdImage $image, int $colorIndexStart): void
    {
        self::setRemap($image, 243, $colorIndexStart);
    }

    public static function setSecondaryRemap(GdImage $image, int $colorIndexStart): void
    {
        self::setRemap($image, 202, $colorIndexStart);
    }

    public static function setTertiaryRemap(GdImage $image, int $colorIndexStart): void
    {
        self::setRemap($image, 46, $colorIndexStart);
    }
}