<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

interface StringTableOwner
{
    public function getStringTable(int $index = 0): array;
}
