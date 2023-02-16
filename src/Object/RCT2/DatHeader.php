<?php
namespace RCTPHP\Object\RCT2;

use RCTPHP\Binary;
use function dechex;
use function fread;
use function fseek;
use function str_pad;
use function strtoupper;
use const SEEK_CUR;
use const STR_PAD_LEFT;

/**
 * Class DatHeader
 *
 * Reads the header of an RCT2 .DAT object file and saves its metadata.
 */
class DatHeader
{
    public const OBJECT_TYPE_RIDE = 0;
    public const OBJECT_TYPE_SMALL_SCENERY = 1;
    public const OBJECT_TYPE_LARGE_SCENERY = 2;
    public const OBJECT_TYPE_WALLS = 3;
    public const OBJECT_TYPE_BANNERS = 4;
    public const OBJECT_TYPE_PATHS = 5;
    public const OBJECT_TYPE_PATH_BITS = 6;
    public const OBJECT_TYPE_SCENERY_GROUP = 7;
    public const OBJECT_TYPE_PARK_ENTRANCE = 8;
    public const OBJECT_TYPE_WATER = 9;
    public const OBJECT_TYPE_SCENARIO_TEXT = 10;

    public const DAT_HEADER_SIZE = 16;

    // Folders as used by the objexport tool
    public const TYPE_TO_FOLDER = [
        "ride",
        "scenery_small",
        "scenery_large",
        "scenery_wall",
        "footpath_banner",
        "footpath",
        "footpath_item",
        "scenery_group",
        "park_entrance",
        "water",
        "scenario_text", // Scenario text objects are not supposed to be converted
    ];

    public readonly int $flags;
    public readonly string $name;
    public readonly int $checksum;

    /**
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->flags = Binary::readUint32($stream);
        $this->name = fread($stream, 8); // ASCII string
        $this->checksum = Binary::readUint32($stream);
    }

    public function getType(): int
    {
        return $this->flags & 0x0F;
    }

    /**
     * @param resource $stream
     * @return static|null
     */
    public static function try(&$stream): self|null
    {
        // A "null entry" or end of list is marked by setting the first byte to 0xFF.
        $peek = Binary::readUint8($stream);
        if ($peek === 0xFF)
        {
            fseek($stream, self::DAT_HEADER_SIZE - 1, SEEK_CUR);
            return null;
        }

        fseek($stream, -1, SEEK_CUR);
        return new static($stream);
    }

    public function toOpenRCT2SceneryGroupNotation(): string
    {
        $flags = str_pad(strtoupper(dechex($this->flags)), 8, '0', STR_PAD_LEFT);
        return "\$DAT:{$flags}|{$this->name}";
    }
}
