<?php
require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1];

$fileObj = new \RCTPHP\ExternalTools\RCT2PaletteMakerFile($filename);
$object = $fileObj->toOpenRCT2Object();
echo json_encode($object->properties->palettes->toArray());

