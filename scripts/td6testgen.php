<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$filename = $argv[1] ?? '';

if (empty($filename))
{
    echo "No filename specified!\n";
    exit(1);
}

$filenameUnRLE = $filename . '.unrle';

$writer = \Cyndaron\BinaryHandler\BinaryWriter::fromFile($filenameUnRLE);

const MAX_TRAINS_PER_RIDE = 32;
const NUM_COLOUR_SCHEMES = 4;

// Ride Type
$writer->writeUint8(\RCTPHP\RCT2\RideType::MULTI_DIMENSION_ROLLER_COASTER->value);
// Vehicle Type
$writer->writeUint8(0);
// Special features
$writer->writeUint32(0);
// Operating mode
$writer->writeUint8(\RCTPHP\RCT2\OperatingMode::CONTINUOUS_CIRCUIT->value);
// Color scheme and version
$writer->writeUint8(\RCTPHP\RCT12\TrackDesignVersion::RCT2->value << 2 | \RCTPHP\RCT12\VehicleColorScheme::ALL_SAME_COLOR->value);

for ($i = 0; $i < MAX_TRAINS_PER_RIDE; $i++)
{
    // body
    $writer->writeUint8(0);
    // trim
    $writer->writeUint8(0);
}

// Padding
$writer->writeUint8(0);
// Entrance Style
$writer->writeUint8(\RCTPHP\RCT12\EntranceStyle::ABSTRACT->value);
// Total air time
$writer->writeUint8(0);
// Departure control flags
$writer->writeUint8(0);
// Number of trains
$writer->writeUint8(1);
// Cars per train
$writer->writeUint8(4);
// Minimum waiting time
$writer->writeUint8(0);
// Max waiting time
$writer->writeUint8(0);
// Extra operating setting
$writer->writeUint8(0);

{
    // max speed
    $writer->writeUint8(0);
    // avg speed
    $writer->writeUint8(0);
    // length
    $writer->writeUint16(100);
    // max pos g
    $writer->writeUint8(0);
    // max neg g
    $writer->writeSint8(0);
    // max lat g
    $writer->writeUint8(0);
    // inversion + coverage
    $writer->writeUint8(0);
    // drops
    $writer->writeUint8(0);
    // highest drop height
    $writer->writeUint8(4);
    // excitement
    $writer->writeUint8(5);
    // intensity
    $writer->writeUint8(5);
    // nausea
    $writer->writeUint8(0);
}

// Upkeep
$writer->writeUint16(100);

for ($i = 0; $i < NUM_COLOUR_SCHEMES; $i++)
{
    $writer->writeUint8(0);
    $writer->writeUint8(0);
    $writer->writeUint8(0);
}

// Special features 2
$writer->writeUint32(0);

{
    // Flags
    $writer->writeUint32(0x0A188A80);
    //  DAT name
    $writer->writeBytes("ARRX    ");
    // Checksum
    $writer->writeUint32(0x9BA47335);
}

// Space req. X
$writer->writeUint8(10);
// Space req. Y
$writer->writeUint8(15);

for ($i = 0; $i < MAX_TRAINS_PER_RIDE; $i++)
{
    // additional
    $writer->writeUint8(0);
}

$numCircuits = 1;
$liftHillSpeed = 5;
$liftHillSpeedAndNumCircuits = ($liftHillSpeed & 0b00011111) | ($numCircuits << 5);

$writer->writeUint8($liftHillSpeedAndNumCircuits);

for ($i = 0; $i < 255; $i++)
{
    $writer->writeUint8($i);
    $writer->writeUint8(0b0000_0100);
}

// End of track data
$writer->writeUint8(0xFF);

// End of entrance data
$writer->writeUint8(0xFF);

// End of scenery data
$writer->writeUint8(0xFF);

$contents = file_get_contents($filenameUnRLE);
$rle = (new \RCTPHP\Sawyer\RLE\NonRLEString($contents))->encode();

$rleWriter = \Cyndaron\BinaryHandler\BinaryWriter::fromFile($filename);
$rleWriter->writeBytes($rle->getRaw());
$rleWriter->writeUint32($rle->getChecksum());
