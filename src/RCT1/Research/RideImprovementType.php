<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\Research;

/**
 * Note: this is based on https://tid.rctspace.com/Sv4/SV4.html#SPECIAL%20TRACK
 * and might not be 100% correct;
 */
enum RideImprovementType : int
{
    case UNKNOWN_00 = 0;
    case UNKNOWN_01 = 1;
    case UNKNOWN_02 = 2;
    case UNKNOWN_03 = 3;
    case UNKNOWN_04 = 4;
    case UNKNOWN_05 = 5;
    case BANKED_CURVES = 6;
    case VERTICAL_LOOP = 7;
    case UNKNOWN_08 = 8;
    case UNKNOWN_09 = 9;
    case UNKNOWN_10 = 10;
    case UNKNOWN_11 = 11;
    case STEEP_TWIST = 12;
    case UNKNOWN_13 = 13;
    case UNKNOWN_14 = 14;
    case UNKNOWN_15 = 15;
    case UNKNOWN_16 = 16;
    case INLINE_TWIST = 17;
    case HALF_LOOP = 18;
    case CORKSCREW = 19;
    case UNKNOWN_20 = 20;
    case BANKED_HELIX_1 = 21;
    case BANKED_HELIX_2 = 22;
    case HELIX_2 = 23;
    case UNKNOWN_24 = 24;
    case UNKNOWN_25 = 25;
    case ONRIDE_PHOTO = 26;
    case WATER_SPLASH = 27;
    case VERTICAL_DROP = 28;
    case BARREL_ROLL = 29;
    case LAUNCHED_LIFT_HILL = 30;
    case LARGE_HALF_LOOP = 31;
    case UNKNOWN_32 = 32;
    case REVERSER_TURNTABLE = 33;
    case HEARTLINE_ROLL = 34;
    case REVERSER = 35;
}
