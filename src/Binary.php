<?php
namespace RCTPHP;

use function fread;
use function ord;
use function unpack;

/**
 * This assumes little endian.
 */
final class Binary
{
    public static function readUint8(&$stream): int
    {
        return ord(fread($stream, 1));
    }

    public static function readUint16(&$stream): int
    {
        return unpack('v', fread($stream, 2))[1];
    }

    public static function readUint32(&$stream): int
    {
        return unpack('V', (fread($stream, 4)))[1];
    }
}
