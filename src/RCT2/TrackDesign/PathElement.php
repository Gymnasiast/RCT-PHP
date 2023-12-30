<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\TrackDesign;

use RCTPHP\RCT12\Coordinates\SmallHorizontal;
use RCTPHP\RCT12\Coordinates\SmallVertical;
use RCTPHP\RCT2\Object\DATHeader;

final class PathElement extends SceneryElement
{
    public bool $isQueue;
    public int $edges;
    public bool $isSloped;
    public int $slopeDirection;

    public function __construct(DATHeader $header, SmallHorizontal $x, SmallHorizontal $y, SmallVertical $z, int $edges, bool $isQueue, bool $isSloped, int $slopeDirection)
    {
        $this->header = $header;
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->edges = $edges;
        $this->isQueue = $isQueue;
        $this->isSloped = $isSloped;
        $this->slopeDirection = $slopeDirection;
    }
}
