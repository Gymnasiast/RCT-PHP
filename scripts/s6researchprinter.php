<?php
declare(strict_types=1);

use RCTPHP\RCT12\Research\ResearchItem;
use RCTPHP\RCT2\S6\Chunks\AvailableItemsChunk;
use RCTPHP\RCT2\S6\S6;

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1] ?? '';

if (empty($filename) || !file_exists($filename))
{
    die("File does not exist!\n");
}

$s6 = S6::createFromFilename($filename);

$printItem = static function(AvailableItemsChunk $availableItemsChunk, ResearchItem $item)
{
    if ($item instanceof \RCTPHP\RCT2\Research\Entry\VehicleEntry)
    {
        $name = '';
        if (array_key_exists($item->index, $availableItemsChunk->vehicles))
        {
            $name = $availableItemsChunk->vehicles[$item->index]->name;
        }

        echo "Vehicle entry: ride type " . $item->rideType->name . ", index {$item->index}, DAT name {$name}\n";
    }
    elseif ($item instanceof \RCTPHP\RCT2\Research\Entry\SceneryEntry)
    {
        $name = '';
        if (array_key_exists($item->index, $availableItemsChunk->sceneryGroups))
        {
            $name = $availableItemsChunk->sceneryGroups[$item->index]->name;
        }

        echo "Scenery entry: index {$item->index}, DAT name {$name}\n";

    }
    else
    {
        var_dump($item);
    }
};

$list = $s6->researchLists;

echo "Invented items:\n";
foreach ($list->inventedItems as $item)
{
    $printItem($s6->availableItemsChunk, $item);
}
echo "\n";

echo "Uninvented items:\n";
foreach ($list->uninventedItems as $item)
{
    $printItem($s6->availableItemsChunk, $item);
}
echo "\n";
