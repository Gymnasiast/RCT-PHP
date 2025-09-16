<?php
declare(strict_types=1);

namespace RCTPHP\RCT12;

enum CursorID : int
{
    case ARROW = 0;
    case BLANK = 1;
    case UP_ARROW = 2;
    case UP_DOWN_ARROW = 3;
    case HAND_POINT = 4;
    case ZZZ = 5;
    case DIAGONAL_ARROWS = 6;
    case PICKER = 7;
    case TREE_DOWN = 8;
    case FOUNTAIN_DOWN = 9;
    case STATUE_DOWN = 10;
    case BENCH_DOWN = 11;
    case CROSS_HAIR = 12;
    case BIN_DOWN = 13;
    case LAMPPOST_DOWN = 14;
    case FENCE_DOWN = 15;
    case FLOWER_DOWN = 16;
    case PATH_DOWN = 17;
    case DIG_DOWN = 18;
    case WATER_DOWN = 19;
    case HOUSE_DOWN = 20;
    case VOLCANO_DOWN = 21;
    case WALK_DOWN = 22;
    case PAINT_DOWN = 23;
    case ENTRANCE_DOWN = 24;
    case HAND_OPEN = 25;
    case HAND_CLOSED = 26;
    case BULLDOZER = 27;

    case Undefined = 0xFF;
}