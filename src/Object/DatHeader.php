<?php
namespace RCTPHP\Object;

use RuntimeException;

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

    public int $flags = 0;
    public string $name = '';
    public int $checksum = 0;

    public function __construct(?string $filename = null)
    {
        if ($filename === null)
        {
            return;
        }

        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }

        $this->flags = unpack('V', (fread($fp, 4)))[1]; // 32-bit little endian
        $this->name = fread($fp, 8); // ASCII string
        $this->checksum = unpack('V', (fread($fp, 4)))[1];   // 32-bit little endian

        fclose($fp);
    }

    public function getType(): int
    {
        return $this->flags & 0x0F;
    }

    public static function fromStream(&$stream): self
    {
        $header = new self();
        $header->flags = unpack('V', (fread($stream, 4)))[1]; // 32-bit little endian
        $header->name = fread($stream, 8); // ASCII string
        $header->checksum = unpack('V', (fread($stream, 4)))[1];   // 32-bit little endian

        return $header;
    }
}
