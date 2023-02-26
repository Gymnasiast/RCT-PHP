<?php
declare(strict_types=1);

namespace RCTPHP\Wave;

use function fopen;
use function fwrite;
use function rewind;

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
}
