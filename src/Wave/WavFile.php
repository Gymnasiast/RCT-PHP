<?php
declare(strict_types=1);

namespace RCTPHP\Wave;

use RCTPHP\Binary;
use function fopen;
use function fread;
use function fseek;
use function fwrite;
use function rewind;
use function var_dump;
use const SEEK_CUR;

final class WavFile
{
    public function __construct(
        public readonly string $header,
        public readonly string $pcmData
    ) {
    }

    public function getHeader(): Header
    {
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $this->header);
        rewind($fp);
        return new Header($fp);
    }

    public function write(string $filename): void
    {
        $pcmDataSizeBytes = pack('V', strlen($this->pcmData));

        $fmtChunk = "fmt \x10\x00\x00\x00" . $this->header;
        $pcmChunk = "data" . $pcmDataSizeBytes . $this->pcmData;

        $file = "WAVE" . $fmtChunk . $pcmChunk;
        $size = strlen($file);
        $headerSizeBytes = pack('V', $size);
        $file = "RIFF" . $headerSizeBytes . $file;

        file_put_contents($filename, $file);
    }

    /**
     * @param resource $fp
     * @return static
     */
    public static function createFromFile($fp): self
    {
        fseek($fp, 4, SEEK_CUR); // RIFF
        fseek($fp, 4, SEEK_CUR); // size
        fseek($fp, 4, SEEK_CUR); // WAVE
        fseek($fp, 4, SEEK_CUR); // fmt
        fseek($fp, 4, SEEK_CUR); // \x10\x00\x00\x00

        $header = fread($fp, Header::SIZE);

        fseek($fp, 4, SEEK_CUR); // data
        $pcmDataSize = Binary::readUint32($fp);
        var_dump($pcmDataSize);
        $pcmData = fread($fp, $pcmDataSize);
        return new self($header, $pcmData);
    }
}
