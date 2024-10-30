<?php
declare(strict_types=1);

namespace RCTPHP\Util\PNG;

use Cyndaron\BinaryHandler\BinaryReader;
use Cyndaron\BinaryHandler\BinaryWriter;
use function pack;
use function unpack;

final class File
{
    public const HEADER = "\x89\x50\x4E\x47\x0D\x0A\x1A\x0A";

    /**
     * @param Chunk[] $chunks
     */
    public function __construct(
        public array $chunks,
    ) {
    }

    public static function create(BinaryReader $reader): self
    {
        $header = $reader->readBytes(8);
        if ($header !== self::HEADER)
        {
            throw new \RuntimeException('Incorrect header, is this a valid PNG file?');
        }

        /** @var Chunk[] $chunks */
        $chunks = [];

        while ($reader->getPosition() < $reader->getSize())
        {
            $length = $reader->readUint32BE();
            $code = $reader->readBytes(4);
            $data = $reader->readBytes($length);
            $crc = $reader->readUint32BE();

            $chunks[] = new Chunk($length, $code, $data, $crc);
        }

        return new self($chunks);
    }

    public function write(BinaryWriter $writer): void
    {
        $writer->writeBytes(self::HEADER);
        foreach ($this->chunks as $chunk)
        {
            $writer->writeBytes(pack('N', $chunk->length));
            $writer->writeBytes($chunk->code);
            $writer->writeBytes($chunk->data);
            $writer->writeBytes(pack('N', $chunk->crc));
        }
    }
}
