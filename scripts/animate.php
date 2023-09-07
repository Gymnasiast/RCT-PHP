<?php

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT2\Object\DATDetector;

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1];
$paletteFilename = $argv[2];

$image = imagecreatefrompng($filename);

$extension = strtolower(pathinfo($paletteFilename, PATHINFO_EXTENSION));
if ($extension === 'bmp')
{
    $obj = new \RCTPHP\ExternalTools\RCT2PaletteMakerFile($paletteFilename);
}
elseif ($extension === 'dat')
{
    $reader = BinaryReader::fromFile($paletteFilename);
    $detector = new DATDetector($reader);
    $obj = $detector->getObject();
}

$converted = $obj->toOpenRCT2Object();
$parts = $converted->properties->palettes->getParts();
$colorsWaves = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::WAVES_0->value]->colors;
$colorsSparkles = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::SPARKLES_0->value]->colors;

const WAVE_START = 230;
const SPARKLE_START = 235;

$numAnimationFrames = 15;
$stitchcommand = "/usr/bin/apngasm animated.png";
$images = [];

$group = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::GENERAL->value];

$offset = $group->index;
for ($index = 0; $index < $group->numColors; $index++)
{
    $rgb = $group->colors[$index];
    imagecolorset($image, $index + $offset, $rgb->r, $rgb->g, $rgb->b);
}

//imagepng($image, 'paletted.png');
//exit(0);

for ($currentFrame = 0; $currentFrame < $numAnimationFrames; $currentFrame++)
{
    $actualFrame = $numAnimationFrames - $currentFrame;

    for ($j = 0; $j < 5; $j++)
    {
        $subIndex = ($actualFrame + (3 * $j)) % 15;
        $rgb = $colorsWaves[$subIndex];
        imagecolorset($image, WAVE_START + $j, $rgb->r, $rgb->g, $rgb->b);
        $rgb = $colorsSparkles[$subIndex];
        imagecolorset($image, SPARKLE_START + $j, $rgb->r, $rgb->g, $rgb->b);
    }

    $imageName = "animate/$currentFrame.png";
    imagepng($image, $imageName);
    $images[] = $imageName;
    $stitchcommand .= " $imageName 1 10";
}

//exec($stitchcommand);
echo ' /usr/bin/php8.1 ../png.php ' . implode(' ', $images);


//
//$temp = new Imagick();
//foreach ($images as $pngImage)
//{
//    $temp->readImage($pngImage);
//    $temp->setImageDelay(5);
//}
//
//$temp->setImageFormat('png');
//$apng = $temp->coalesceImages();
//
//$apng->setImageFormat('webp');
//$apng->setImageIterations(0);
//
//
////file_put_contents('animate/all.webp', $apng->getImagesBlob());
//$apng->writeImages('animate/all.webp', true);

