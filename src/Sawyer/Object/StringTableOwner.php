<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

interface StringTableOwner
{
    /**
     * @return array<string, StringTable>
     */
    public function getStringTables(): array;

    public function getStringTable(string $name = 'name'): StringTable;
}
