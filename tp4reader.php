<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/RCT1/TP4/Palette.php';


if ($argc < 2)
{
    echo "No filename specified!\n";
    exit(1);
}

$filename = $argv[1];

$fp = fopen($filename, 'rb');
fseek($fp, 400);

const HEIGHT = 200;
const WIDTH = 254;

$image = imagecreate(WIDTH, HEIGHT);
foreach (\RCTPHP\RCT1\TP4\PALETTE as $index => $color)
{
    $id = imagecolorallocate($image, $color->r, $color->g, $color->b);
    if ($id !== $index)
    {
        throw new \Exception("Incorrect index for color {$index}!");
    }
}


for ($lineNum = 0; $lineNum < HEIGHT; $lineNum++)
{
    $startFlag = \RCTPHP\Binary::readUint16($fp);
    for ($i = 0; $i < 127; $i++)
    {
        $index = \RCTPHP\Binary::readUint8($fp);
        imagesetpixel($image, $i, $lineNum, $index);
    }
    $midFlag = \RCTPHP\Binary::readUint16($fp);
    for ($i = 0; $i < 127; $i++)
    {
        $index = \RCTPHP\Binary::readUint8($fp);
        imagesetpixel($image, $i + 127, $lineNum, $index);
    }
}

imagepng($image, 'converted.png');
