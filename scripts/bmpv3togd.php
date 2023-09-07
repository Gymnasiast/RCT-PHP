<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1];

$reader = \Cyndaron\BinaryHandler\BinaryReader::fromFile($filename);

$magic = $reader->readUint16();
$fileSize = $reader->readUint32();
$reserved1 = $reader->readUint16();
$reserved2 = $reader->readUint16();
$startOffset = $reader->readUint32();

$dibHeaderSize = $reader->readUint32();
if ($dibHeaderSize !== 56)
{
    throw new RuntimeException('Only support for v3info!');
}

$imageWidth = $reader->readUint32();
$imageHeight = $reader->readUint32();
$colorPlanes = $reader->readUint16();
$bpp = $reader->readUint16();
$compressionMethod = $reader->readUint32();
if ($compressionMethod !== 3)
{
    throw new RuntimeException('Only support for bitfields!');
}

$imageDataSize = $reader->readUint32();
$horizontalRes = $reader->readUint32();
$verticalRes = $reader->readUint32();
$numColors = $reader->readUint32();
$numImportantColors = $reader->readUint32();

$bitfieldsRed = $reader->readUint32();
$bitfieldsGreen = $reader->readUint32();
$bitfieldsBlue = $reader->readUint32();
$bitfieldsAlpha = $reader->readUint32();

$gd = imagecreatetruecolor($imageWidth, $imageHeight);

for ($y = $imageHeight - 1; $y >= 0; $y--)
{
    for ($x = 0; $x < $imageWidth; $x++)
    {
        $b = $reader->readUint8();
        $g = $reader->readUint8();
        $r = $reader->readUint8();
        $a = $reader->readUint8();

        $color = imagecolorallocatealpha($gd, $r, $g, $b, 0);
        imagesetpixel($gd, $x, $y, $color);
    }
}

//imagepng($gd, 'convertedbmp.png');
imagebmp($gd, 'convertedbmp.bmp');
