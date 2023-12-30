<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

enum ObjectType: int
{
    case Ride = 0;
    case SmallScenery = 1;
    case LargeScenery = 2;
    case Wall = 3;
    case Banner = 4;
    case Footpath = 5;
    case PathAddition = 6;
    case SceneryGroup = 7;
    case ParkEntrance = 8;
    case Water = 9;
    case ScenarioText = 10;
}
