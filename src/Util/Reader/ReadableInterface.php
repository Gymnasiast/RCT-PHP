<?php
declare(strict_types=1);

namespace RCTPHP\Util\Reader;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;

interface ReadableInterface
{
    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): static|self;

    public static function tryFromReader(ReaderInterface&IntegerReaderInterface $reader): static|self|null;
}
