<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1];

$reader = \Cyndaron\BinaryHandler\BinaryReader::fromFile($filename);

$image = \RCTPHP\Util\PCX\PCXImage::read($reader);
$gd = $image->exportAsGdImage();
imagepng($gd, 'converted2.png');
