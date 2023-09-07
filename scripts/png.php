<?php
declare(strict_types=1);

use Cyndaron\BinaryHandler\BinaryReader;
use Cyndaron\BinaryHandler\BinaryWriter;
use RCTPHP\Util\PNG\Animated;
use RCTPHP\Util\PNG\File;

require __DIR__ . '/../vendor/autoload.php';

$numFiles = $argc - 1;
if ($numFiles < 2)
{
    throw new \RuntimeException('Specify at least 2 files!');
}

$filenames = [];
for ($index = 1; $index <= $numFiles; $index++)
{
    $filenames[] = $argv[$index];
}

/** @var File[] $files */
$files = [];

foreach ($filenames as $i => $filename)
{
    $gd = imagecreatefrompng($filename);
    imagepalettetotruecolor($gd);
    $tempNam = tempnam('/tmp', 'animator-' . $i);
    imagepng($gd, $tempNam);

    $reader = BinaryReader::fromFile($tempNam);
    $file = File::create($reader);

    $files[] = $file;
}

$animated = new Animated($files);
$writer = BinaryWriter::fromFile('animated.png');
$animated->getFile()->write($writer);
