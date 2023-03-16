<?php
declare(strict_types=1);

namespace TXweb\BinaryHandler;

use RuntimeException;
use function fopen;
use function fread;
use function ord;
use function unpack;

final class BinaryReader extends BinaryHandler
{
    public static function fromFile(string $filename): static
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }
        return new self($fp, true);
    }

    public function readBytes(int $numBytes): string
    {
        $ret = @fread($this->fp, $numBytes);
        if ($ret === false)
        {
            throw new RuntimeException('Could not read data!');
        }
        return $ret;
    }

    public function readUint8(): int
    {
        return ord($this->readBytes(1));
    }

    public function readUint16(): int
    {
        return unpack('v', $this->readBytes(2))[1];
    }

    public function readUint32(): int
    {
        return unpack('V', ($this->readBytes(4)))[1];
    }

    public function readSint8(): int
    {
        return unpack('c', ($this->readBytes(1)))[1];
    }

    public function readSint16(): int
    {
        return unpack('s', ($this->readBytes(2)))[1];
    }

    public function readSint32(): int
    {
        return unpack('l', ($this->readBytes(4)))[1];
    }
}
