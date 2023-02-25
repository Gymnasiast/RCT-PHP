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
    /**
     * @param resource $stream
     * @return int
     */
    public static function readUint8(&$stream): int
    {
        return ord(fread($stream, 1));
    }

    /**
     * @param resource $stream
     * @return int
     */
    public static function readUint16(&$stream): int
    {
        return unpack('v', fread($stream, 2))[1];
    }

    /**
     * @param resource $stream
     * @return int
     */
    public static function readUint32(&$stream): int
    {
        return unpack('V', (fread($stream, 4)))[1];
    }

    /**
     * @param resource $stream
     * @return int
     */
    public static function readSint8(&$stream): int
    {
        return unpack('c', (fread($stream, 1)))[1];
    }

    /**
     * @param resource $stream
     * @return int
     */
    public static function readSint16(&$stream): int
    {
        return unpack('s', (fread($stream, 2)))[1];
    }

    /**
     * @param resource $stream
     * @return int
     */
    public static function readSint32(&$stream): int
    {
        return unpack('l', (fread($stream, 4)))[1];
    }
}
