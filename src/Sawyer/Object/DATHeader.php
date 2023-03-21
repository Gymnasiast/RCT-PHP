<?php
namespace RCTPHP\Sawyer\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use function dechex;
use function str_pad;
use function strtoupper;
use const STR_PAD_LEFT;

/**
 * Class DATHeader
 *
 * Reads the header of an RCT2 or Locomotion .DAT object file and saves its metadata.
 */
abstract class DATHeader
{
    final public const DAT_HEADER_SIZE = 16;

    public readonly int $flags;
    public readonly string $name;
    public readonly int $checksum;

    final public function __construct(BinaryReader $reader)
    {
        $this->flags = $reader->readUint32();
        $this->name = $reader->readBytes(8); // ASCII string
        $this->checksum = $reader->readUint32();
    }

    final public static function try(BinaryReader $reader): self|null
    {
        // A "null entry" or end of list is marked by setting the first byte to 0xFF.
        $peek = $reader->readUint8();
        if ($peek === 0xFF)
        {
            $reader->seek(self::DAT_HEADER_SIZE - 1);
            return null;
        }

        $reader->seek(-1);
        return new static($reader);
    }

    abstract public function getType(): int;

    final public function getFlagsFormatted(): string
    {
        return str_pad(strtoupper(dechex($this->flags)), 8, '0', STR_PAD_LEFT);
    }

    final public function getChecksumFormatted(): string
    {
        return str_pad(strtoupper(dechex($this->checksum)), 8, '0', STR_PAD_LEFT);
    }
}
