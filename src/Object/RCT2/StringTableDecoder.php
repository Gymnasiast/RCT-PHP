<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\RCT2String;
use function fread;
use function ord;

trait StringTableDecoder
{
    /**
     * @param resource $fp
     * @return void
     */
    public function readStringTable(&$fp): void
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

            $this->stringTable[] = new RCT2String($languageCode, $string);
        }
    }

    public function getStringTable(): array
    {
        return $this->stringTable;
    }
}
