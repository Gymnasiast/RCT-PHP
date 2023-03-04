<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/src/RCT1/TP4/Palette.php';

if ($argc < 3)
{
    echo "No filename specified!\n";
    exit(1);
}

$filenameIn = $argv[1];
$filenameOut = $argv[2];

$image = imagecreatefrompng($filenameIn);
$tp4File = new \RCTPHP\RCT1\TP4\TP4File($image, true);
$tp4File->writeTP4($filenameOut);
$header = $tp4File->generateHeader();
file_put_contents('header.bin', $header);
