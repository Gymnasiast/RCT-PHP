<?php
declare(strict_types=1);

namespace RCTPHP;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use Exception;
use RCTPHP\Sawyer\ChunkEncoding;
use RCTPHP\Sawyer\RLE\RLEString;
use RuntimeException;
use Cyndaron\BinaryHandler\BinaryReader;
use function chr;
use function ord;
use function str_pad;
use function strlen;
use function substr;

final class Util
{
    public static function printLn(string $input): void
    {
        echo $input, PHP_EOL;
    }

    public static function ror8(int $input, int $shift): int
    {
        $rotated = ($input >> $shift) | $input << (8 - $shift);
        return $rotated & 0b11111111;
    }

    public static function rol32(int $input, int $shift): int
    {
        $rotated = ($input << $shift) | ($input >> (32 - $shift));
        return $rotated & 0xFFFFFFFF;
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
        $rleDecoded = (new RLEString($input))->decode();
        $repeatDecoded = self::decodeRepeat($rleDecoded);

        return $repeatDecoded;
    }

    public static function readChunk(ReaderInterface&IntegerReaderInterface $reader): string
    {
        $encoding = ChunkEncoding::from($reader->readUint8());
        $restLength = $reader->readUint32();
        $rest = $reader->readBytes($restLength);

        return match ($encoding)
        {
            ChunkEncoding::NONE => $rest,
            ChunkEncoding::RLE => (new RLEString($rest))->decode(),
            ChunkEncoding::RLE_REPEAT => self::decodeRLERepeat($rest),
            ChunkEncoding::ROTATE => self::decodeRotate($rest),
        };
    }
}
