<?php
declare(strict_types=1);

namespace RCTPHP\RCT2;

enum Color : int
{
    // Original Colours
    case BLACK = 0;
    case GRAY = 1;
    case WHITE = 2;
    case DARK_PURPLE = 3;
    case LIGHT_PURPLE = 4;
    case BRIGHT_PURPLE = 5;
    case DARK_BLUE = 6;
    case LIGHT_BLUE = 7;
    case ICY_BLUE = 8;
    case TEAL = 9;
    case AQUAMARINE = 10;
    case SATURATED_GREEN = 11;
    case DARK_GREEN = 12;
    case MOSS_GREEN = 13;
    case BRIGHT_GREEN = 14;
    case OLIVE_GREEN = 15;
    case DARK_OLIVE_GREEN = 16;
    case BRIGHT_YELLOW = 17;
    case YELLOW = 18;
    case DARK_YELLOW = 19;
    case LIGHT_ORANGE = 20;
    case DARK_ORANGE = 21;
    case LIGHT_BROWN = 22;
    case SATURATED_BROWN = 23;
    case DARK_BROWN = 24;
    case SALMON_PINK = 25;
    case BORDEAUX_RED = 26;
    case SATURATED_RED = 27;
    case BRIGHT_RED = 28;
    case DARK_PINK = 29;
    case BRIGHT_PINK = 30;
    case LIGHT_PINK = 31;

    // Extended Colour Set
    case DARK_OLIVE_DARK = 32;        // Army green
    case DARK_OLIVE_LIGHT = 33;       // Honeydew
    case SATURATED_BROWN_LIGHT = 34;  // Tan
    case BORDEAUX_RED_DARK = 35;      // Maroon
    case BORDEAUX_RED_LIGHT = 36;     // Coral pink
    case GRASS_GREEN_DARK = 37;       // Forest green
    case GRASS_GREEN_LIGHT = 38;      // Chartreuse
    case OLIVE_DARK = 39;             // Hunter green
    case OLIVE_LIGHT = 40;            // Celadon
    case SATURATED_GREEN_LIGHT = 41;  // Lime green
    case TAN_DARK = 42;               // Sepia
    case TAN_LIGHT = 43;              // Peach
    case DULL_PURPLE_LIGHT = 44;      // Periwinkle
    case DULL_GREEN_DARK = 45;        // Viridian
    case DULL_GREEN_LIGHT = 46;       // Seafoam green
    case SATURATED_PURPLE_DARK = 47;  // Violet
    case SATURATED_PURPLE_LIGHT = 48; // Lavender
    case ORANGE_LIGHT = 49;           // Pastel orange
    case AQUA_DARK = 50;              // Deep water
    case MAGENTA_LIGHT = 51;          // Pastel pink
    case DULL_BROWN_DARK = 52;        // Umber
    case DULL_BROWN_LIGHT = 53;       // Beige
    case INVISIBLE = 54;              // Invisible
    case VOID = 55;                   // Void
}
