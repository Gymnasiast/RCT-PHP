<?php
declare(strict_types=1);

namespace RCTPHP\Util\Reader;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;

trait FromReaderTrait
{
    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): static|self
    {
        $ret = static::tryFromReader($reader);
        if ($ret === null)
        {
            throw new \RuntimeException('Null returned!');
        }
        return $ret;
    }
}
