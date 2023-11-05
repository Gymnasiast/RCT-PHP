<?php
declare(strict_types=1);

namespace RCTPHP\RCT12;

enum VehicleColorScheme : int
{
    case ALL_SAME_COLOR = 0;
    case DIFFERENT_COLOR_PER_TRAIN = 1;
    case DIFFERENT_COLOR_PER_CAR = 2;
}
