<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\S6\Chunks;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\Sawyer\ChunkEncoding;
use RCTPHP\Sawyer\RLE\RLEString;
use RCTPHP\Util;

final class Chunk
{
    public function __construct(public readonly ChunkHeader $header, public readonly string $decodedContents)
    {
    }

    public static function createFromReader(BinaryReader $reader): self
    {
        $header = ChunkHeader::createFromReader($reader);
        $buffer = $reader->readBytes($header->length);
        switch ($header->encoding)
        {
            case ChunkEncoding::NONE:
                break;
            case ChunkEncoding::RLE:
                $rleString = new RLEString($buffer);
                $buffer = $rleString->decode();
                break;
            case ChunkEncoding::RLE_REPEAT:
                $buffer = Util::decodeRLERepeat($buffer);
                break;
            case ChunkEncoding::ROTATE:
                $buffer = Util::decodeRotate($buffer);
                break;
        }

        return new self($header, $buffer);
    }
}
