<?php
declare(strict_types=1);

use RCTPHP\RCT1\S4\S4;
use RCTPHP\RCT12\Research\ResearchItem;

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1] ?? '';
$decodeSC4 = (bool)(int)($argv[2] ?? '');

if (empty($filename) || !file_exists($filename))
{
    die("File does not exist!\n");
}

$reader = \Cyndaron\BinaryHandler\BinaryReader::fromFile($filename);

$s4 = S4::fromReader($reader);
$table = $s4->researchLists;

$printItem = static function(ResearchItem $item)
{
    if ($item instanceof \RCTPHP\RCT1\Research\Entry\RideEntry)
    {
        echo "Ride entry: ride type " . $item->type->name . "\n";
    }
    elseif ($item instanceof \RCTPHP\RCT1\Research\Entry\VehicleEntry)
    {
        echo "Vehicle entry: ride type " . $item->rideType->name . ", vehicle type {$item->type->name}\n";
    }
    elseif ($item instanceof \RCTPHP\RCT1\Research\Entry\SceneryEntry)
    {
        echo "Scenery entry: item {$item->type->name}\n";
    }
    elseif ($item instanceof \RCTPHP\RCT1\Research\Entry\RideImprovementEntry)
    {
        echo "Ride improvement: type {$item->type->name}, ride type {$item->rideType->name}\n";
    }
    else
    {
        var_dump($item);
    }
};

$list = $s4->researchLists;

echo "Invented items:\n";
foreach ($list->inventedItems as $item)
{
    $printItem($item);
}
echo "\n";

echo "Uninvented items:\n";
foreach ($list->uninventedItems as $item)
{
    $printItem($item);
}
echo "\n";
echo "Random pairs:\n";
$numRandom = count($list->randomItems);
for ($i = 0; $i < $numRandom; $i++)
{
    $pair = $list->randomItems[$i];
    $printItem($pair->item1);
    $printItem($pair->item2);
    echo "\n";
}
