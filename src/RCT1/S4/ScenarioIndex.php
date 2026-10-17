<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\S4;

enum ScenarioIndex : int
{
    // Base game
    case FOREST_FRONTIERS = 0;
    case DYNAMITE_DUNES = 1;
    case LEAFY_LAKE = 2;
    case DIAMOND_HEIGHTS = 3;
    case EVERGREEN_GARDENS = 4;
    case BUMBLY_BEACH = 5;
    case TRINITY_ISLANDS = 6;
    case KATIES_DREAMLAND = 7;
    case POKEY_PARK = 8;
    case WHITE_WATER_PARK = 9;
    case MILLENNIUM_MINES = 10;
    case KARTS_COASTERS = 11;
    case MELS_WORLD = 12;
    case MYSTIC_MOUNTAIN = 13;
    case PACIFIC_PYRAMIDS = 14;
    case CRUMBLY_WOODS = 15;
    case PARADISE_PIER = 16;
    case LIGHTNING_PEAKS = 17;
    case IVORY_TOWERS = 18;
    case RAINBOW_VALLEY = 19;
    case THUNDER_ROCK = 20;
    case MEGA_PARK = 21;

    // Loopy Landscapes
    case ICEBERG_ISLANDS = 22;
    case VOLCANIA = 23;
    case ARID_HEIGHTS = 24;
    case RAZOR_ROCKS = 25;
    case CRATER_LAKE = 26;
    case VERTIGO_VIEWS = 27;
    case PARADISE_PIER_2 = 28;
    case DRAGONS_COVE = 29;
    case GOOD_KNIGHT_PARK = 30;
    case WACKY_WARREN = 31;

    // Special
    case ALTON_TOWERS = 32;
    case FORT_ANACHRONISM = 33;
    case TAIWAN_PARK = 34;
 
    // Added Attractions
    case WHISPERING_CLIFFS = 40;
    case THREE_MONKEYS_PARK = 41;
    case CANARY_MINES = 42;
    case BARONY_BRIDGE = 43;
    case FUNTOPIA = 44;
    case HAUNTED_HARBOUR = 45;
    case FUN_FORTRESS = 46;
    case FUTURE_WORLD = 47;
    case GENTLE_GLEN = 48;
    case JOLLY_JUNGLE = 49;
    case HYDRO_HILLS = 50;
    case SPRIGHTLY_PARK = 51;
    case MAGIC_QUARTERS = 52;
    case FRUIT_FARM = 53;
    case BUTTERFLY_DAM = 54;
    case COASTER_CANYON = 55;
    case THUNDERSTORM_PARK = 56;
    case HARMONIC_HILLS = 57;
    case ROMAN_VILLAGE = 58;
    case SWAMP_COVE = 59;
    case ADRENALINE_HEIGHTS = 60;
    case UTOPIA_PARK = 61;
    case ROTTING_HEIGHTS = 62;
    case FIASCO_FOREST = 63;
    case PICKLE_PARK = 64;
    case GIGGLE_DOWNS = 65;
    case MINERAL_PARK = 66;
    case COASTER_CRAZY = 67;
    case URBAN_PARK = 68;
    case GEOFFREY_GARDENS = 69;

    // Special
    case HEIDE_PARK = 70;
    case PCPLAYER = 71;
    case PCGW = 72;
    case GAMEPLAY = 73;
    case BLACKPOOL_PLEASURE_BEACH = 74;
 
    // Loopy Landscapes
    case GRAND_GLACIER = 80;
    case CRAZY_CRATERS = 81;
    case DUSTY_DESERT = 82;
    case WOODWORM_PARK = 83;
    case ICARUS_PARK = 84;
    case SUNNY_SWAMPS = 85;
    case FRIGHTMARE_HILLS = 86;
    case THUNDER_ROCKS = 87;
    case OCTAGON_PARK = 88;
    case PLEASURE_ISLAND = 89;
    case ICICLE_WORLDS = 90;
    case SOUTHERN_SANDS = 91;
    case TINY_TOWERS = 92;
    case NEVERMORE_PARK = 93;
    case PACIFICA = 94;
    case URBAN_JUNGLE = 95;
    case TERROR_TOWN = 96;
    case MEGAWORLD_PARK = 97;
    case VENUS_PONDS = 98;
    case MICRO_PARK = 99;

    // Custom
    case RCTOA_ACRES = 100;
    case CUSTOM_1 = 101;

    // Competition scenarios (Build the best ... you can)
    case STEEL_TWISTER_COMPETITION = 110;
    case GO_KARTS_COMPETITION = 114;
    case STEEL_CORKSCREW_COMPETITION = 116;
    case MINE_TRAIN_COMPETITION = 117;
    case STEEL_ROLLER_COASTER_COMPETITION = 119;
    case BOBSLED_COMPETITION = 120;
    case INVERTED_ROLLER_COASTER_COMPETITION = 123;
    case SUSPENDED_ROLLER_COASTER_COMPETITION = 124;
    case STAND_UP_STEEL_ROLLER_COASTER_COMPETITION = 125;
    case WOODEN_ROLLER_COASTER_COMPETITION = 126;
    case STEEL_MINI_ROLLER_COASTER_COMPETITION = 127;
}
