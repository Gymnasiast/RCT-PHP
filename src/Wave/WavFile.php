<?php
declare(strict_types=1);

namespace RCTPHP\Wave;

use Cyndaron\BinaryHandler\BinaryReader;
use function var_dump;

final class WavFile
{
    public function __construct(
        public readonly string $header,
        public readonly string $pcmData
    ) {
    }

    public function getHeader(): Header
    {
        $reader = BinaryReader::fromString($this->header);
        return new Header($reader);
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
     * @param BinaryReader $reader
     * @return static
     */
    public static function createFromFile(BinaryReader $reader): self
    {
        $reader->seek(4); // RIFF
        $reader->seek(4); // size
        $reader->seek(4); // WAVE
        $reader->seek(4); // fmt
        $reader->seek(4); // \x10\x00\x00\x00

        $header = $reader->readBytes(Header::SIZE);

        $reader->seek(4); // data
        $pcmDataSize = $reader->readUint32();
        $pcmData = $reader->readBytes($pcmDataSize);
        return new self($header, $pcmData);
    }
}
