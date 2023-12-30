<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

use RCTPHP\RCT12\Coordinates\SmallHorizontal;
use RCTPHP\RCT12\Coordinates\SmallVertical;
use RCTPHP\RCT2\Color;
use RCTPHP\RCT2\Object\DATHeader;

final class SmallSceneryElement extends SceneryElement
{
    public int $direction;
    public int $quadrant;
    public Color $firstColor;
    public Color $secondColor;

    public function __construct(DATHeader $header, SmallHorizontal $x, SmallHorizontal $y, SmallVertical $z, int $direction, int $quadrant, Color $firstColor, Color $secondColor)
    {
        $this->header = $header;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->direction = $direction;
        $this->quadrant = $quadrant;
        $this->firstColor = $firstColor;
        $this->secondColor = $secondColor;
    }
}
