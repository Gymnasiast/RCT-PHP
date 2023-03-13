<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\SawyerString;
use RCTPHP\Util;
use function array_key_exists;
use function fread;
use function ord;

trait StringTableDecoder
{
    /**
     * @param resource $fp
     * @param int $index
     * @return void
     */
    public function readStringTable(&$fp, string $name): void
    {
        if (!array_key_exists($name, $this->stringTable))
        {
            $this->stringTable[$name] = new StringTable();
        }

        while (true)
        {
            $languageCode = ord(fread($fp, 1));
            if ($languageCode === 0xFF)
            {
                break;
            }

            $string = '';
            while (true)
            {
                $character = fread($fp, 1);
                if (ord($character) === 0)
                {
                    break;
                }

                $string .= $character;
            }

            $this->stringTable[$name]->strings[] = new SawyerString($languageCode, $string);
        }
    }

    public function getStringTable(string $name = 'name'): StringTable
    {
        return $this->stringTable[$name];
    }

    public function printStringTables(): void
    {
        foreach ($this->stringTable as $name => $stringTable)
        {
            Util::printLn("String table “{$name}”:");
            foreach ($stringTable->strings as $stringTableItem)
            {
                Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
            }
        }
    }
}
