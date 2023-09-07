<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Util;
use RCTPHP\Wave\Header;
use RCTPHP\Wave\WavFile;
use Cyndaron\BinaryHandler\BinaryReader;
use function is_dir;
use function mkdir;
use function trim;

class SoundObject implements LocomotionObject, StringTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];

    public readonly int $volume;
    /** @var WavFile[] */
    public readonly array $soundData;

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);
        $reader->seek(0x06);
        $var06 = $reader->readUint8();
        $pad07 = $reader->readUint8();
        $this->volume = $reader->readSint32();

        $this->readStringTable($reader, 'name');

        $numSamples = $reader->readUint32();
        $lengthOfSoundData = $reader->readUint32();

        $reader->seek($numSamples * 16);

        $soundData = [];
        for ($i = 0; $i < $numSamples; $i++)
        {
            $var00 = $reader->readSint32();
            $offset = $reader->readSint32();
            $length = $reader->readUint32();
            $header = $reader->readBytes(Header::SIZE);
            $pcmData = $reader->readBytes($length);

            $soundData[] = new WavFile($header, $pcmData);
        }
        $this->soundData = $soundData;
    }

//    public function printData(): void
//    {
//
//        $foldername = 'export/' . trim($this->header->name);
//        if (!is_dir($foldername))
//        {
//            mkdir($foldername, recursive: true);
//        }
//
//        foreach ($this->soundData as $index => $soundFile)
//        {
//            $soundFile->write("$foldername/{$index}.wav");
//        }
//    }
}
