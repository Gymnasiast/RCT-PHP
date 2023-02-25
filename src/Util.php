<?php
declare(strict_types=1);

namespace RCTPHP;

use Exception;
use RCTPHP\Sawyer\ChunkEncoding;
use RuntimeException;
use ValueError;
use function chr;
use function fclose;
use function file_put_contents;
use function fread;
use function ord;
use function str_pad;
use function strlen;

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

    public static function ror8(int $input, int $shift): int
    {
         $rotated = ($input >> $shift) | $input << (8 - $shift);
         return $rotated & 0b11111111;
    }

    public static function decodeRotate(string $input): string
    {
        $srcLength = strlen($input);
        $output = str_pad('', $srcLength, chr(0));
        $code = 1;

        for ($i = 0; $i < $srcLength; $i++)
        {
            $byte = ord($input[$i]);
            $out = self::ror8($byte, $code);
            $code = ($code + 2) % 8;
            $output[$i] = chr($out);
        }

        return $output;
    }

    public static function decodeRepeat(string $src8): string
    {
        $srcLength = strlen($src8);
        $output = '';

        for ($i = 0; $i < $srcLength; $i++)
        {
            if (ord($src8[$i]) === 0xFF)
            {
                $output .= $src8[++$i];
            }
            else
            {
                $count = (ord($src8[$i]) & 7) + 1;
                $copySrc = strlen($output) + (ord($src8[$i]) >> 3) - 32;

                if ($copySrc < 0)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }
                if (($copySrc < (strlen($output) + $count) && $copySrc >= strlen($output))
                    || (($copySrc + $count) <= (strlen($output) + $count) && ($copySrc + $count) > strlen($output)))
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }

                $output .= substr($output, $copySrc, $count);
            }
        }
        return $output;
    }

    public static function decodeRLERepeat(string $input): string
    {
        $rleDecoded = self::decodeRLE($input);
        file_put_contents('decoded1', $rleDecoded);

        $repeatDecoded = self::decodeRepeat($rleDecoded);
        file_put_contents('decoded2', $repeatDecoded);

        return $repeatDecoded;
    }

    /**
     * @param resource $stream
     * @return string
     *
     * @throws ValueError
     * @throws Exception
     */
    public static function readChunk($stream): string
    {
        $encoding = ChunkEncoding::from(Binary::readUint8($stream));
        $restLength = Binary::readUint32($stream);
        $rest = fread($stream, $restLength);

        return match ($encoding)
        {
            ChunkEncoding::NONE => $rest,
            ChunkEncoding::RLE => self::decodeRLE($rest),
            ChunkEncoding::RLE_REPEAT => self::decodeRLERepeat($rest),
            ChunkEncoding::ROTATE => self::decodeRotate($rest),
        };
    }
}
