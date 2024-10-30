<?php

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT2\Object\DATDetector;

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1];
$paletteFilename = $argv[2];

$baseImage = imagecreatefrompng($filename);

$extension = strtolower(pathinfo($paletteFilename, PATHINFO_EXTENSION));
if ($extension === 'bmp')
{
    $obj = new \RCTPHP\ExternalTools\RCT2PaletteMakerFile($paletteFilename);
}
elseif ($extension === 'dat')
{
    $reader = BinaryReader::fromFile($paletteFilename);
    $detector = DATDetector::fromReader($reader);
    $obj = $detector->getObject();
}

$converted = $obj->toOpenRCT2Object();
$parts = $converted->properties->palettes->getParts();
$paletteWaves = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::WAVES_0->value];
$paletteSparkles = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::SPARKLES_0->value];

const WAVE_START = 230;
const SPARKLE_START = 235;
const WAVE_PALETTE_START = 256;
const SPARKLE_PALETTE_START = 271;

$numAnimationFrames = 15;
$images = [];

$offset = 10;
$group = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::GENERAL->value];
for ($index = 0; $index < $group->numColors; $index++)
{
    $rgb = $group->colors[$index];
    imagecolorset($baseImage, $index + $offset, $rgb->r, $rgb->g, $rgb->b);
}

$offset = WAVE_PALETTE_START;
for ($index = 0; $index < $paletteWaves->numColors; $index++)
{
    $rgb = $paletteWaves->colors[$index];
    imagecolorset($baseImage, $index + $offset, $rgb->r, $rgb->g, $rgb->b);
}
$offset = SPARKLE_PALETTE_START;
for ($index = 0; $index < $paletteSparkles->numColors; $index++)
{
    $rgb = $paletteWaves->colors[$index];
    imagecolorset($baseImage, $index + $offset, $rgb->r, $rgb->g, $rgb->b);
}

$baseImageFilename = tempnam('/tmp', 'baseImage');
imagepng($baseImage, $baseImageFilename);

$imagesToCleanUp = [$baseImageFilename];

/** @var \RCTPHP\Util\PNG\File[] $files */
$files = [];

for ($currentFrame = 0; $currentFrame < $numAnimationFrames; $currentFrame++)
{
    $actualFrame = $numAnimationFrames - $currentFrame;
    $image = imagecreatefrompng($baseImageFilename);

    $replacements = [];

    for ($j = 0; $j < 5; $j++)
    {
        $subIndex = ($actualFrame + (3 * $j)) % 15;
        $originalWaveIndex = WAVE_START + $j;
        $newWaveIndex = WAVE_PALETTE_START+ $subIndex;
        $originalSparkleIndex = SPARKLE_START + $j;
        $newSparkleIndex = SPARKLE_PALETTE_START + $subIndex;

        $replacements[$originalWaveIndex] = $newWaveIndex;
        $replacements[$originalSparkleIndex] = $newSparkleIndex;
    }

    for ($y = 0; $y < imagesy($image); $y++)
    {
        for ($x = 0; $x < imagesy($image); $x++)
        {
            $index = imagecolorat($image, $x, $y);
            if (array_key_exists($index, $replacements))
            {
                imagesetpixel($image, $x, $y, $replacements[$index]);
            }
        }
    }

    $imageName = "animate/{$currentFrame},png";// tempnam('/tmp', 'animate-' . $currentFrame);
    imagepng($image, $imageName);

    $reader = BinaryReader::fromFile($imageName);
    $files[] = \RCTPHP\Util\PNG\File::create($reader);
    $imagesToCleanUp[] = $imageName;
}

$animated = new \RCTPHP\Util\PNG\Animated($files);

foreach ($imagesToCleanUp as $filename)
{
    //@unlink($filename);
}

$writer = \Cyndaron\BinaryHandler\BinaryWriter::fromFile('animated3.png');
$animated->getFile()->write($writer);
