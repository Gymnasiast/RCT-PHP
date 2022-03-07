<?php
declare(strict_types=1);

namespace RCTPHP\Object;

interface StringTableOwner
{
    public function getStringTable(): array;
}
