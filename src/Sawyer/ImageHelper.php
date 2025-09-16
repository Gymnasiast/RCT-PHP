<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

use GdImage;
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

//        for ($y = 0; $y < $srcHeight; $y++)
//        {
//            for ($x = 0; $x < $srcWidth; $x++)
//            {
//                $colorIndex = imagecolorat($src, $x, $y);
//                if ($colorIndex !== 0)
//                {
//                    //imagecolorset($dst, )
//                }
//            }
//        }

        imagecopy($dst, $src, $destX, $destY, 0, 0, $srcWidth, $srcHeight);
    }
}