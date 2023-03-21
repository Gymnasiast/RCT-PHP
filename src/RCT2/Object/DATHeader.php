<?php
namespace RCTPHP\RCT2\Object;

use function dechex;
use function str_pad;
use function strtoupper;
use const STR_PAD_LEFT;

/**
 * Class DATHeader
 *
 * Reads the header of an RCT2 .DAT object file and saves its metadata.
 */
final class DATHeader extends \RCTPHP\Sawyer\Object\DATHeader
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

    public function getType(): int
    {
        return $this->flags & 0x0F;
    }

    public function getAsOriginalId(): string
    {
        $flags = $this->getFlagsFormatted();
        $checksum = $this->getChecksumFormatted();
        return "{$flags}|{$this->name}|{$checksum}";
    }

    public function getAsSceneryGroupListEntry(): string
    {
        $flags = $this->getFlagsFormatted();
        return "\$DAT:{$flags}|{$this->name}";
    }
}
