<?php
declare(strict_types=1);

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT2\Object\DATDetector;
use RCTPHP\RCT2\Object\ScenarioTextObject;

require __DIR__ . '/../vendor/autoload.php';

$dir = $argv[1] ?? '';
if (!is_dir($dir)) {
    echo "Folder not found!\n";
    exit(1);
}

$files = scandir($dir);
foreach ($files as $file)
{
    if (!str_ends_with(strtolower($file), '.dat'))
        continue;

    $fullPath = "$dir/$file";
    $reader = BinaryReader::fromFile($fullPath);
    $detector = DATDetector::fromReader($reader);
    $object = $detector->getObject();

    if (!($object instanceof ScenarioTextObject))
        continue;

    $isCustom = ($object->header->flags & 0xF0) === 0;
    if ($isCustom)
        continue;

    $englishDesc = trim($object->getStringTable('scenario_name')->strings[0]->toUtf8());
    if ($englishDesc === '')
        continue;

    echo "{$englishDesc};{$object->header->getAsOriginalId()}\n";
}