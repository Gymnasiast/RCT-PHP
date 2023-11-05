<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\TrackDesign;

use RCTPHP\Sawyer\SawyerTileHeight;

final class Height
{
    public function __construct(public int $rawValue)
    {
    }

    public function toRCT2Internal(): SawyerTileHeight
    {
        return new SawyerTileHeight($this->rawValue / 2);
    }
}
