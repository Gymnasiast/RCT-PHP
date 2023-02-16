<?php
namespace RCTPHP\Locomotion\Object;

/**
 * Class DATHeader
 *
 * Reads the header of a Locomotion .DAT object file and saves its metadata.
 */
final class DATHeader extends \RCTPHP\Sawyer\Object\DATHeader
{
    public const OBJECT_TYPE_INTERFACE = 0;
    public const OBJECT_TYPE_SOUNDS = 1;
    public const OBJECT_TYPE_CURRENCY = 2;
    public const OBJECT_TYPE_STEAM = 3;
    public const OBJECT_TYPE_CLIFF_EDGES = 4;
    public const OBJECT_TYPE_WATER = 5;
    public const OBJECT_TYPE_LAND = 6;
    public const OBJECT_TYPE_TOWN_NAMES = 7;
    public const OBJECT_TYPE_CARGO = 8;
    public const OBJECT_TYPE_WALL = 9;
    public const OBJECT_TYPE_TRACK_SIGNAL = 10;
    public const OBJECT_TYPE_LEVEL_CROSSINGS = 11;
    public const OBJECT_TYPE_STREET_LIGHT = 12;
    public const OBJECT_TYPE_TUNNEL = 13;
    public const OBJECT_TYPE_BRIDGE = 14;
    public const OBJECT_TYPE_TRACK_STATION = 15;
    public const OBJECT_TYPE_TRACK_EXTRA = 16;
    public const OBJECT_TYPE_TRACK = 17;
    public const OBJECT_TYPE_ROAD_STATION = 18;
    public const OBJECT_TYPE_ROAD_EXTRA = 19;
    public const OBJECT_TYPE_ROAD = 20;
    public const OBJECT_TYPE_AIRPORT = 21;
    public const OBJECT_TYPE_DOCK = 22;
    public const OBJECT_TYPE_VEHICLE = 23;
    public const OBJECT_TYPE_TREE = 24;
    public const OBJECT_TYPE_SNOW = 25;
    public const OBJECT_TYPE_CLIMATE = 26;
    public const OBJECT_TYPE_HILL_SHAPES = 27;
    public const OBJECT_TYPE_BUILDING = 28;
    public const OBJECT_TYPE_SCAFFOLDING = 29;
    public const OBJECT_TYPE_INDUSTRY = 30;
    public const OBJECT_TYPE_REGION = 31;
    public const OBJECT_TYPE_COMPETITOR = 32;
    public const OBJECT_TYPE_SCENARIO_TEXT = 33;

    public function getType(): int
    {
        return $this->flags & 0b00111111;
    }
}
