<?php
declare(strict_types=1);

namespace RCTPHP\Util\Reader;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use Throwable;

trait TryFromReaderTrait
{
    public static function tryFromReader(ReaderInterface&IntegerReaderInterface $reader): static|self|null
    {
        try
        {
            $ret = static::fromReader($reader);
            return $ret;
        }
        catch (Throwable)
        {
            return null;
        }
    }
}
