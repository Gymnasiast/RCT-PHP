<?php

use RCTPHP\Util;
use RCTPHP\Wave\Header;
use RCTPHP\Wave\WavFile;
use TXweb\BinaryHandler\BinaryReader;

require __DIR__ . '/vendor/autoload.php';

if ($argc < 3)
{
    echo "Usage: css1datreader.php <input file> <output folder>!\n";
    exit(1);
}

$filename = $argv[1];
$outputFolder = rtrim($argv[2], '/');

$reader = BinaryReader::fromFile($filename);
$numSamples = $reader->readUint32();
$offsetList = [];
for ($i = 0; $i < $numSamples; $i++)
{
    $offsetList[$i] = $reader->readUint32();
}
// To allow +1
$offsetList[$numSamples] = $reader->getSize();

$soundDataSize = $reader->readUint32();
$realSoundDataSize = $reader->getSize() - $reader->getPosition();

Util::printLn("Num samples: {$numSamples}");
for ($i = 0; $i < $numSamples; $i++)
{
    Util::printLn("Offset #{$i}: {$offsetList[$i]}");
}
Util::printLn("Sound data size: {$soundDataSize} / {$realSoundDataSize}");



for ($i = 0; $i < $numSamples; $i++)
{
    $startOffset = $offsetList[$i];
    $size = $offsetList[$i + 1] - $startOffset;
    $reader->moveTo($startOffset + 4);
    $header = $reader->readBytes(Header::SIZE);
    $pcm = $reader->readBytes($size - Header::SIZE);

    //shell_exec("sox -r {$struct->samplesPerSec} -e signed -b {$struct->bitsPerSample} -c {$struct->channels} {$pcmFilename} {$wavFilename}");

    $file = new WavFile($header, $pcm);
    $file->write("{$outputFolder}/{$i}.wav");
}

