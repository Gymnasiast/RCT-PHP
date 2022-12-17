<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use function fread;
use function unpack;

final class Bytes
{
    /**
     * @param resource $fp
     * @return int
     */
    public static function readUint32(&$fp): int
    {
        return unpack('V', (fread($fp, 4)))[1];
    }

    /**
     * @param resource $fp
     * @return int
     */
    public static function readInt32(&$fp): int
    {
        return unpack('l', (fread($fp, 4)))[1];
    }

    /**
     * @param resource $fp
     * @return int
     */
    public static function readUint16(&$fp): int
    {
        return unpack('v', (fread($fp, 2)))[1];
    }

    /**
     * @param resource $fp
     * @return int
     */
    public static function readInt16(&$fp): int
    {
        return unpack('s', (fread($fp, 2)))[1];
    }

    /**
     * @param resource $fp
     * @return int
     */
    public static function readUint8(&$fp): int
    {
        return ord(fread($fp, 1));
    }

    /**
     * @param resource $fp
     * @return int
     */
    public static function readInt8(&$fp): int
    {
        return unpack('s', (fread($fp, 4)))[1];
    }

    /**
     * @param resource $fp
     * @return ImageHeader
     */
    public static function readImageHeader(&$fp): ImageHeader
    {
        $header = new ImageHeader();
        $header->startAddress = self::readUint32($fp);
        $header->width = self::readUint16($fp);
        $header->height = self::readUint16($fp);
        $header->xOffset = self::readUint16($fp);
        $header->yOffset = self::readUint16($fp);
        $header->flags = self::readUint16($fp);
        $header->zoomedOffset = self::readUint16($fp);
        return $header;
    }
}
