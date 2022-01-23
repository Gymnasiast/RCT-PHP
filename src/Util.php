<?php
declare(strict_types=1);

namespace RCTPHP;

use RuntimeException;
use function ord;

final class Util
{
    public static function printLn(string $input): void
    {
        echo $input, PHP_EOL;
    }

    /**
     * Decode a RLE stream in RCT2’s encoding scheme. Code taken from OpenRCT2.
     *
     * @param string $input
     * @return string
     */
    public static function decodeRLE(string $input): string
    {
        $srcLength = strlen($input);
        $output = '';

        for ($i = 0; $i < $srcLength; $i++)
        {
            $rleCodeByte = ord($input[$i]);
            if ($rleCodeByte & 128)
            {
                $i++;
                $count = 257 - $rleCodeByte;

                if ($i >= $srcLength)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }

                for ($c = 0; $c < $count; $c++)
                {
                    $output .= $input[$i];
                }
            }
            else
            {
                if ($i + 1 >= $srcLength)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }
                if ($i + 1 + $rleCodeByte + 1 > $srcLength)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }

                for ($c = 0; $c < $rleCodeByte + 1; $c++)
                {
                    $output .= $input[$i + 1 + $c];
                }

                $i += $rleCodeByte + 1;
            }
        }

        return $output;
    }
}
