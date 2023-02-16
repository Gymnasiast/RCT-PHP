<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\RCT2String;
use RCTPHP\Util;
use function fread;
use function ord;

trait StringTableDecoder
{
    /**
     * @param resource $fp
     * @return void
     */
    public function readStringTable(&$fp, int $index = 0): void
    {
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

            $this->stringTable[$index][] = new RCT2String($languageCode, $string);
        }
    }

    public function getStringTable(int $index = 0): array
    {
        return $this->stringTable[$index];
    }

    public function printStringTables(): void
    {
        foreach ($this->stringTable as $index => $stringTable)
        {
            Util::printLn("String table #{$index}:");
            foreach ($stringTable as $stringTableItem)
            {
                Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
            }
        }
    }
}
