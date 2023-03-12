<?php

use RCTPHP\Binary;
use RCTPHP\Util;
use RCTPHP\Wave\Header;
use RCTPHP\Wave\WavFile;

require __DIR__ . '/vendor/autoload.php';

if ($argc < 3)
{
    echo "Usage: css1datreader.php <input file> <output folder>!\n";
    exit(1);
}

$filename = $argv[1];
$outputFolder = rtrim($argv[2], '/');

$fp = fopen($filename, 'rb');
$numSamples = Binary::readUint32($fp);
$offsetList = [];
for ($i = 0; $i < $numSamples; $i++)
{
    $offsetList[$i] = Binary::readUint32($fp);
}
// To allow +1
$offsetList[$numSamples] = fstat($fp)['size'];

$soundDataSize = Binary::readUint32($fp);
$realSoundDataSize = fstat($fp)['size'] - ftell($fp);

Util::printLn("Num samples: {$numSamples}");
for ($i = 0; $i < $numSamples; $i++)
{
    Util::printLn("Offset #{$i}: {$offsetList[$i]}");
}
Util::printLn("Sound data size: {$soundDataSize} / {$realSoundDataSize}");

//var_dump(ftell($fp));



for ($i = 0; $i < $numSamples; $i++)
{
    $startOffset = $offsetList[$i];
    $size = $offsetList[$i + 1] - $startOffset;
    fseek($fp, $startOffset + 4, SEEK_SET);
    $header = fread($fp, Header::SIZE);
    $pcm = fread($fp, $size - Header::SIZE);

    //shell_exec("sox -r {$struct->samplesPerSec} -e signed -b {$struct->bitsPerSample} -c {$struct->channels} {$pcmFilename} {$wavFilename}");

    $file = new WavFile($header, $pcm);
    $file->write("{$outputFolder}/{$i}.wav");
}

