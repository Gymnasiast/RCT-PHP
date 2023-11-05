<?php
declare(strict_types=1);

namespace RCTPHP\RCT2;

use RCTPHP\RCT12\TrackDesignSpeed;
use RCTPHP\Sawyer\SawyerTileHeight;

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
    public SawyerTileHeight $highestDropHeight;
    public float $excitement;
    public float $intensity;
    public float $nausea;
}
