<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\Object\StringTable;

interface StringTableOwner
{
    public function getStringTable(string $name = 'name'): StringTable;
}
