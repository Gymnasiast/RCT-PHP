<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

enum ObjectType : int
{
    case INTERFACE = 0;
    case SOUNDS = 1;
    case CURRENCY = 2;
    case STEAM = 3;
    case CLIFF_EDGES = 4;
    case WATER = 5;
    case LAND = 6;
    case TOWN_NAMES = 7;
    case CARGO = 8;
    case WALL = 9;
    case TRACK_SIGNAL = 10;
    case LEVEL_CROSSINGS = 11;
    case STREET_LIGHT = 12;
    case TUNNEL = 13;
    case BRIDGE = 14;
    case TRACK_STATION = 15;
    case TRACK_EXTRA = 16;
    case TRACK = 17;
    case ROAD_STATION = 18;
    case ROAD_EXTRA = 19;
    case ROAD = 20;
    case AIRPORT = 21;
    case DOCK = 22;
    case VEHICLE = 23;
    case TREE = 24;
    case SNOW = 25;
    case CLIMATE = 26;
    case HILL_SHAPES = 27;
    case BUILDING = 28;
    case SCAFFOLDING = 29;
    case INDUSTRY = 30;
    case REGION = 31;
    case COMPETITOR = 32;
    case SCENARIO_TEXT = 33;

//    private const MAPPING = [
//        self::INTERFACE->value => InterfaceObject::class,
//        self::SOUNDS->value => SoundObject::class,
//        self::CURRENCY->value => CurrencyObject::class,
//        self::TRACK->value => TrackObject::class,
//        self::SCENARIO_TEXT->value => ScenarioTextObject::class,
//    ];
}
