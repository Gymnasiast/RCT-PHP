<?php

use RCTPHP\RCT1\TP4\TP4File;

require __DIR__ . '/../vendor/autoload.php';

if ($argc < 3)
{
    echo "Usage: tp4reader.php <inputfile> <outputfile>\n";
    exit(1);
}

$inputFilename = $argv[1];
$outputFilename = $argv[2];

$reader = \Cyndaron\BinaryHandler\BinaryReader::fromFile($inputFilename);
$tp4File = TP4File::createFromFile($reader);
$tp4File->writeImage($outputFilename);
