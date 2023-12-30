<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

enum TrackTypeID : int
{
    case FlatTrack1x4A_Alias = 95;
    case RotationControlToggleAlias = 100;
    case InvertedUp90ToFlatQuarterLoopAlias = 101;
    case FlatTrack2x2_Alias = 110;
    case FlatTrack4x4_Alias = 111;
    case FlatTrack2x4_Alias = 115;
    case FlatTrack1x5_Alias = 116;
    case FlatTrack1x1A_Alias = 118;
    case FlatTrack1x4B_Alias = 119;
    case FlatTrack1x1B_Alias = 121;
    case FlatTrack1x4C_Alias = 122;
    case FlatTrack3x3_Alias = 123;
}
