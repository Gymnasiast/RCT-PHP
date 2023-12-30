<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

use RCTPHP\RCT12\Coordinates\SmallHorizontal;
use RCTPHP\RCT12\Coordinates\SmallVertical;
use RCTPHP\RCT2\Object\DATHeader;

abstract class SceneryElement
{
    public DATHeader $header;
    public SmallHorizontal $x;
    public SmallHorizontal $y;
    public SmallVertical $z;
}
