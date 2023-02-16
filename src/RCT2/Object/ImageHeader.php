<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

final class ImageHeader
{
    public const FLAG_BMP = (1 << 0); // Image data is encoded as raw pixels (no transparency)
    public const FLAG_1 = (1 << 1);
    public const FLAG_RLE_COMPRESSION = (1 << 2); // Image data is encoded using RCT2's form of run length encoding
    public const FLAG_PALETTE = (1 << 3);         // Image data is a sequence of palette entries R8G8B8
    public const FLAG_HAS_ZOOM_SPRITE = (1 << 4); // Use a different sprite for higher zoom levels
    public const FLAG_NO_ZOOM_DRAW = (1 << 5);    // Does not get drawn at higher zoom levels (only zoom 0)

    public const FLAGS = [
        self::FLAG_BMP,
        self::FLAG_1,
        self::FLAG_RLE_COMPRESSION,
        self::FLAG_PALETTE,
        self::FLAG_HAS_ZOOM_SPRITE,
        self::FLAG_NO_ZOOM_DRAW,
    ];

    public int $startAddress; // uint32, 0x00
    public int $width;        // uint16, 0x04
    public int $height;       // uint16, 0x06
    public int $xOffset;      // uint16, 0x08
    public int $yOffset;      // uint16, 0x0A
    public int $flags;        // uint16, 0x0C
    public int $zoomedOffset; // int32_t, 0x0E

    public function hasFlag(int $flag): bool
    {
        return (bool)($this->flags & $flag);
    }
}
