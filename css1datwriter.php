<?php
declare(strict_types=1);

use RCTPHP\Util;
use RCTPHP\Wave\WavFile;
use TXweb\BinaryHandler\BinaryReader;
use TXweb\BinaryHandler\BinaryWriter;

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
    $reader = BinaryReader::fromFile($fullFilename);
    $readFiles[$file] = WavFile::createFromFile($reader);
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

$writer = BinaryWriter::fromFile($outputFilename);
$writer->writeUint32($numSamples);
foreach ($offsetList as $i => $offset)
{
    Util::printLn("File $i, offset {$offset}");
    $writer->writeUint32($offset);
}

$writer->writeUint32(strlen($binarySoundData));
$writer->writeBytes($binarySoundData);
