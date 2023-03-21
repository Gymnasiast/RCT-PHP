<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\SawyerString;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;
use function array_key_exists;
use function ord;

trait StringTableDecoder
{
    public function readStringTable(BinaryReader $reader, string $name): void
    {
        if (!array_key_exists($name, $this->stringTable))
        {
            $this->stringTable[$name] = new StringTable();
        }

        while (true)
        {
            $languageCode = $reader->readUint8();
            if ($languageCode === 0xFF)
            {
                break;
            }

            $string = '';
            while (true)
            {
                $character = $reader->readBytes(1);
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
