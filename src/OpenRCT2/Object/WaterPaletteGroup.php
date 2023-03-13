<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

enum WaterPaletteGroup : string
{
    case GENERAL = 'general';
    case WAVES_0 = 'waves-0';
    case WAVES_1 = 'waves-1';
    case WAVES_2 = 'waves-2';
    case SPARKLES_0 = 'sparkles-0';
    case SPARKLES_1 = 'sparkles-1';
    case SPARKLES_2 = 'sparkles-2';
}
