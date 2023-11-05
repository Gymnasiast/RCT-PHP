<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1] ?? '';

if (empty($filename))
{
    echo "No filename specified!\n";
    exit(1);
}

$td6 = \RCTPHP\RCT2\TrackDesign\TD6::createFromFile($filename);
var_dump($td6);
var_dump($td6->statictics->maximumSpeed->asKmh());
var_dump($td6->statictics->averageSpeed->asKmh());
var_dump($td6->statictics->highestDropHeight->asMetres());
