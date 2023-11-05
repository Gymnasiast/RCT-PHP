<?php
declare(strict_types=1);

namespace RCTPHP\RCT1;

use RCTPHP\RCT1\TrackDesign\Height;
use RCTPHP\RCT12\TrackDesignSpeed;

class RideStatictics
{
    public TrackDesignSpeed $maximumSpeed;
    public TrackDesignSpeed $averageSpeed;
    public int $length;
    public int $maximumPositiveG;
    public int $maximumNegativeG;
    public int $maximumLateralG;
    public int $numInversionsOrHoles;
    public int $numDrops;
    public Height $highestDropHeight;
    public float $excitement;
    public float $intensity;
    public float $nausea;
}
