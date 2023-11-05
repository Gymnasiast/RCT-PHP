<?php
declare(strict_types=1);

namespace RCTPHP\RCT1;

enum OperatingMode: int
{
    case NORMAL = 0;
    case CONTINUOUS_CIRCUIT = 1;
    case REVERSE_INCLINE_LAUNCHED_SHUTTLE = 2;
    case POWERED_LAUNCH = 3; // Never passes through station
    case SHUTTLE = 4;
    case BOAT_HIRE = 5;
    case UPWARD_LAUNCH = 6;
    case ROTATING_LIFT = 7;
    case STATION_TO_STATION = 8;
    case SINGLE_RIDE_PER_ADMISSION = 9;
    case UNLIMITED_RIDES_PER_ADMISSION = 10;
    case MAZE = 11;
    case RACE = 12;
    case DODGEMS = 13;
    case SWING = 14;
    case SHOP_STALL = 15;
    case ROTATION = 16;
    case FORWARD_ROTATION = 17;
    case BACKWARD_ROTATION = 18;
    case FILM_AVENGING_AVIATORS = 19;
    case _3D_FILM_MOUSE_TAILS = 20;
    case SPACE_RINGS = 21;
    case BEGINNERS = 22;
    case LIM_POWERED_LAUNCH = 23;
    case FILM_THRILL_RIDERS = 24;
    case _3D_FILM_STORM_CHASERS = 25;
    case _3D_FILM_SPACE_RAIDERS = 26;
    case INTENSE = 27;
    case BERSERK = 28;
    case HAUNTED_HOUSE = 29;
    case CIRCUS_SHOW = 30;
    case DOWNWARD_LAUNCH = 31;
    case CROOKED_HOUSE = 32;
    case FREEFALL_DROP = 33;
}
