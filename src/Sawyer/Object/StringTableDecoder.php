<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\Sawyer\SawyerString;
use RCTPHP\Sawyer\SawyerStringLanguage;
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

            $language = SawyerStringLanguage::from($languageCode);
            $this->stringTable[$name]->strings[$languageCode] = new SawyerString($language, $string);
        }
    }

    public function getStringTable(string $name = 'name'): StringTable
    {
        return $this->stringTable[$name];
    }

    public function getStringTables(): array
    {
        return $this->stringTable;
    }
}
