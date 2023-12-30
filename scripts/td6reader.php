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

foreach ($td6->trackElements as $trackElement)
{
    $desc = \RCTPHP\OpenRCT2\Track\TrackPieceNames::get($trackElement->trackPiece);
    echo "$desc\n";
    //var_dump($trackElement);
}

foreach ($td6->mazeElements as $mazeElement)
{
    echo "x: {$mazeElement->x->value}, y: {$mazeElement->y->value}\n";
    echo $mazeElement->getAsASCIIArt();
}

var_dump($td6->sceneryElements);
