<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1] ?? '';

if (empty($filename))
{
    echo "No filename specified!\n";
    exit(1);
}

$td4 = \RCTPHP\RCT1\TrackDesign\TD4::createFromFile($filename);
var_dump($td4);
var_dump($td4->statictics->maximumSpeed->asKmh());
var_dump($td4->statictics->averageSpeed->asKmh());
var_dump($td4->statictics->highestDropHeight->toRCT2Internal()->asMetres());
