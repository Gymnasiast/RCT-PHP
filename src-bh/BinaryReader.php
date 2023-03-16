<?php
declare(strict_types=1);

namespace TXweb\BinaryHandler;

use RuntimeException;
use function fopen;
use function fread;

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
}
