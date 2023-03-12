<?php

use RCTPHP\Binary;
use RCTPHP\Util;
use RCTPHP\Wave\Header;
use RCTPHP\Wave\WavFile;

require __DIR__ . '/vendor/autoload.php';

if ($argc < 3)
{
    echo "Usage: css1datreader.php <input folder> <output file>!\n";
    exit(1);
}

$inputFolder = rtrim($argv[1], '/');
$outputFilename = $argv[2];

$files = array_filter(scandir($inputFolder), static function(string $filename)
{
    if (substr($filename, 0, 1) === '.')
        return false;

    if (strtolower(substr($filename, -4)) !== '.wav')
        return false;

    return true;
});
natsort($files);
$numSamples = count($files);
$readFiles = [];
foreach ($files as $file)
{
    $fullFilename = "$inputFolder/$file";
    $fp = fopen($fullFilename, 'rb');
    $readFiles[$file] = WavFile::createFromFile($fp);
    fclose($fp);
}

$startOffset = 4 + ($numSamples * 4);

$offsetList = [];

$binarySoundData = '';
foreach ($readFiles as $name => $readFile)
{
    $offsetList[] = strlen($binarySoundData) + $startOffset;
    echo "File {$name}, channels {$readFile->getHeader()->channels}\n";
    $binarySoundData .= $readFile->header . $readFile->pcmData;
}

//$offsetList[] = strlen($binarySoundData);

$fp = fopen($outputFilename, 'wb');
Binary::writeUint32($fp, $numSamples);
foreach ($offsetList as $i => $offset)
{
    Util::printLn("File $i, offset {$offset}");
    Binary::writeUint32($fp, $offset);
}
Binary::writeUint32($fp, strlen($binarySoundData));


fwrite($fp, $binarySoundData);

fclose($fp);
