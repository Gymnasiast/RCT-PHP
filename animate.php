<?php
require __DIR__ . '/vendor/autoload.php';

$filename = $argv[1];
$paletteFilename = $argv[2];

$image = imagecreatefrompng($filename);

$obj = new \RCTPHP\ExternalTools\RCT2PaletteMakerFile($paletteFilename);
$table = $obj->toOpenRCT2Palette();
$parts = $table->getParts();
$colorsWaves = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::WAVES_0->value]->colours;
$colorsSparkles = $parts[\RCTPHP\OpenRCT2\Object\WaterPaletteGroup::SPARKLES_0->value]->colours;

const WAVE_START = 230;
const SPARKLE_START = 235;

$numAnimationFrames = 15;
$stitchcommand = "/usr/bin/apngasm animated.png";
$images = [];
for ($currentFrame = 0; $currentFrame < $numAnimationFrames; $currentFrame++)
{
    for ($j = 0; $j < 5; $j++)
    {
        $actualFrame = $numAnimationFrames - $currentFrame;
        $subIndex = ($actualFrame + (3 * $j)) % 15;
        $rgb = $colorsWaves[$subIndex];
        imagecolorset($image, WAVE_START + $j, $rgb->r, $rgb->g, $rgb->b);
        $rgb = $colorsSparkles[$subIndex];
        imagecolorset($image, SPARKLE_START + $j, $rgb->r, $rgb->g, $rgb->b);
        $imageName = "animate/$currentFrame.png";
        imagepng($image, $imageName);
        $images[] = $imageName;
        $stitchcommand .= " $imageName 1 10";
    }
}

exec($stitchcommand);


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

