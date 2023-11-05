<?php
require __DIR__ . '/../vendor/autoload.php';

use RCTPHP\RCT1\TrackDesign\TP4File;

if ($argc < 3)
{
    echo "Usage: tp4writer.php <inputfile> <outputfile>\n";
    exit(1);
}

$filenameIn = $argv[1];
$filenameOut = $argv[2];

$image = imagecreatefrompng($filenameIn);
$tp4File = new TP4File($image, true);
$tp4File->writeTP4($filenameOut);
