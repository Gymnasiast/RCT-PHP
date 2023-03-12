<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use RCTPHP\Binary;
use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\RCT2\Object\StringTableDecoder;
use RCTPHP\RCT2\Object\StringTableOwner;
use RCTPHP\RCT2String;
use RCTPHP\Util;
use RCTPHP\Wave\Header;
use RCTPHP\Wave\WavFile;
use function fclose;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function is_dir;
use function mkdir;
use function rewind;
use function trim;
use const SEEK_CUR;

class SoundObject implements DATObject, StringTableOwner
{
    use StringTableDecoder;

    public DATHeader $header;
    /** @var RCT2String[][] */
    public array $stringTable = [];

    public readonly int $volume;
    /** @var WavFile[] */
    public readonly array $soundData;

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 0x06, SEEK_CUR);
        $var06 = Binary::readUint8($fp);
        $pad07 = Binary::readUint8($fp);
        $this->volume = Binary::readSint32($fp);

        $this->readStringTable($fp, 0);

        $numSamples = Binary::readUint32($fp);
        $lengthOfSoundData = Binary::readUint32($fp);

        fseek($fp, $numSamples * 16, SEEK_CUR);

        $soundData = [];
        for ($i = 0; $i < $numSamples; $i++)
        {
            $var00 = Binary::readSint32($fp);
            $offset = Binary::readSint32($fp);
            $length = Binary::readUint32($fp);
            $header = fread($fp, Header::SIZE);
            $pcmData = fread($fp, $length);

            $soundData[] = new WavFile($header, $pcmData);
        }
        $this->soundData = $soundData;

        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Volume: {$this->volume}");

        $this->printStringTables();

        $foldername = 'export/' . trim($this->header->name);
        if (!is_dir($foldername))
        {
            mkdir($foldername, recursive: true);
        }

        foreach ($this->soundData as $index => $soundFile)
        {
            $soundFile->write("$foldername/{$index}.wav");
        }
    }
}
