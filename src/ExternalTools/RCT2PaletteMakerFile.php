<?php
declare(strict_types=1);

namespace RCTPHP\ExternalTools;

use GdImage;
use RCTPHP\OpenRCT2\Object\WaterObject;
use RCTPHP\OpenRCT2\Object\WaterProperties;
use RCTPHP\OpenRCT2\Object\WaterPropertiesPalettes;
use RCTPHP\RCT2\Object\ObjectWithOpenRCT2Counterpart;
use RCTPHP\Util\RGB;
use RuntimeException;
use function imagecolorat;
use function imagecreatefrombmp;
use function imagesx;
use function floor;

final class RCT2PaletteMakerFile implements ObjectWithOpenRCT2Counterpart
{
    private const NUM_COLORS = 326;

    private readonly GdImage $image;
    private readonly bool $allowDucks;

    public function __construct(string $filename, bool $allowDucks = false)
    {
        $image = @imagecreatefrombmp($filename);
        if ($image === false)
        {
            throw new RuntimeException('Could not read input file, is it a BMP file?');
        }

        $this->image = $image;
        $this->allowDucks = $allowDucks;
    }

    private function getPalettes(): WaterPropertiesPalettes
    {
        $palettes = new WaterPropertiesPalettes();
        // Normally, this is 16, but the RCT2 Palette Editor can apparently handle any width.
        $width = imagesx($this->image);

        for ($i = 0; $i < self::NUM_COLORS; $i++)
        {
            $y = (int)floor($i / $width);
            $x = $i % $width;

            $color = imagecolorat($this->image, $x, $y);
            $r = ($color >> 16) & 0xFF;
            $g = ($color >> 8) & 0xFF;
            $b = $color & 0xFF;

            $rgb = new RGB($r, $g, $b);

            $palettes->setColorByAbsoluteIndex($i, $rgb);
        }

        return $palettes;
    }

    public function toOpenRCT2Object(): WaterObject
    {
        return new WaterObject(new WaterProperties($this->allowDucks, $this->getPalettes()));
    }
}
