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

    final public function __construct(
        public readonly int $flags,
        public readonly string $name,
        public readonly int $checksum,
    ) {
    }

    private function isNull(): bool
    {
        return $this->flags === 0xFFFFFFFF;
    }

    final public static function fromReader(BinaryReader $reader): static
    {
        $candidate = self::tryFromReader($reader);
        if ($candidate === null)
        {
            throw new \RuntimeException('Entry is null!');
        }

        return $candidate;
    }

    final public static function tryFromReader(BinaryReader $reader): static|null
    {
        $flags = $reader->readUint32();
        $name = $reader->readBytes(8); // ASCII string
        $checksum = $reader->readUint32();
        $candidate = new static($flags, $name, $checksum);
        if ($candidate->isNull())
        {
            return null;
        }

        return $candidate;
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
