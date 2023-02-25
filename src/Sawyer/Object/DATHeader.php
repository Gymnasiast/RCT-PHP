<?php
namespace RCTPHP\Sawyer\Object;

use RCTPHP\Binary;
use function dechex;
use function fread;
use function fseek;
use function str_pad;
use function strtoupper;
use const SEEK_CUR;
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

    /**
     * @param resource $stream
     */
    final public function __construct($stream)
    {
        $this->flags = Binary::readUint32($stream);
        $this->name = fread($stream, 8); // ASCII string
        $this->checksum = Binary::readUint32($stream);
    }

    /**
     * @param resource $stream
     * @return static|null
     */
    final public static function try(&$stream): self|null
    {
        // A "null entry" or end of list is marked by setting the first byte to 0xFF.
        $peek = Binary::readUint8($stream);
        if ($peek === 0xFF)
        {
            fseek($stream, self::DAT_HEADER_SIZE - 1, SEEK_CUR);
            return null;
        }

        fseek($stream, -1, SEEK_CUR);
        return new static($stream);
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
