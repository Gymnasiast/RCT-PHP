<?php
declare(strict_types=1);

namespace TXweb\BinaryHandler;

use RuntimeException;
use function fopen;
use function fwrite;
use function pack;

final class BinaryWriter extends BinaryHandler
{
    public static function fromFile(string $filename): static
    {
        $fp = fopen($filename, 'wb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }
        return new self($fp, true);
    }

    public function writeBytes(string $bytes): int
    {
        $ret = @fwrite($this->fp, $bytes);
        if ($ret === false)
        {
            throw new RuntimeException('Could not write data!');
        }
        return $ret;
    }

    public function writeUint32(int $value): int
    {
        return $this->writeBytes(pack('V', $value));
    }
}
