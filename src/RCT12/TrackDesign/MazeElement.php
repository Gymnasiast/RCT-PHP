<?php
declare(strict_types=1);

namespace RCTPHP\RCT12\TrackDesign;

use RCTPHP\RCT12\Coordinates\SmallHorizontal;

class MazeElement
{
    // Right = x+, top = y+
    public const BOTTOM_LEFT_QUADRANT = (1 << 3);
    public const TOP_LEFT_QUADRANT = (1 << 7);
    public const TOP_RIGHT_QUADRANT = (1 << 11);
    public const BOTTOM_RIGHT_QUADRANT = (1 << 15);

    public const LEFT_INTERIOR_WALL = (1 << 2);
    public const TOP_INTERIOR_WALL = (1 << 6);
    public const RIGHT_INTERIOR_WALL = (1 << 10);
    public const BOTTOM_INTERIOR_WALL = (1 << 14);

    public const BOTTOM_LEFT_OUTER_WALL = (1 << 0);
    public const LEFT_BOTTOM_OUTER_WALL = (1 << 1);
    public const LEFT_TOP_OUTER_WALL = (1 << 4);
    public const TOP_LEFT_OUTER_WALL = (1 << 5);
    public const TOP_RIGHT_OUTER_WALL = (1 << 8);
    public const RIGHT_TOP_OUTER_WALL = (1 << 9);
    public const RIGHT_BOTTOM_OUTER_WALL = (1 << 12);
    public const BOTTOM_RIGHT_OUTER_WALL = (1 << 13);

    public function __construct(
        public readonly SmallHorizontal $x,
        public readonly SmallHorizontal $y,
        public readonly int $mazeEntry,
    ) {
    }

    public function getAsASCIIArt(): string
    {
        $output = '';

        $tlow = ($this->mazeEntry & self::TOP_LEFT_OUTER_WALL) ? '-' : ' ';
        $trow = ($this->mazeEntry & self::TOP_RIGHT_OUTER_WALL) ? '-' : ' ';
        $output .= "*{$tlow}*{$trow}*\n";

        $ltow = ($this->mazeEntry & self::LEFT_TOP_OUTER_WALL) ? '|' : ' ';
        $tlq = ($this->mazeEntry & self::TOP_LEFT_QUADRANT) ? '*' : ' ';
        $tiw = ($this->mazeEntry & self::TOP_INTERIOR_WALL) ? '|' : ' ';
        $trq = ($this->mazeEntry & self::TOP_RIGHT_QUADRANT) ? '*' : ' ';
        $rtow = ($this->mazeEntry & self::RIGHT_TOP_OUTER_WALL) ? '|' : ' ';
        $output .= "{$ltow}{$tlq}{$tiw}{$trq}{$rtow}\n";

        $liw = ($this->mazeEntry & self::LEFT_INTERIOR_WALL) ? '-' : ' ';
        $riw = ($this->mazeEntry & self::RIGHT_INTERIOR_WALL) ? '-' : ' ';
        $output .= "*{$liw}*{$riw}*\n";

        $lbow = ($this->mazeEntry & self::LEFT_BOTTOM_OUTER_WALL) ? '|' : ' ';
        $blq = ($this->mazeEntry & self::BOTTOM_LEFT_QUADRANT) ? '*' : ' ';
        $biw = ($this->mazeEntry & self::BOTTOM_INTERIOR_WALL) ? '|' : ' ';
        $brq = ($this->mazeEntry & self::BOTTOM_RIGHT_QUADRANT) ? '*' : ' ';
        $rbow = ($this->mazeEntry & self::RIGHT_BOTTOM_OUTER_WALL) ? '|' : ' ';
        $output .= "{$lbow}{$blq}{$biw}{$brq}{$rbow}\n";

        $blow = ($this->mazeEntry & self::BOTTOM_LEFT_OUTER_WALL) ? '-' : ' ';
        $brow = ($this->mazeEntry & self::BOTTOM_RIGHT_OUTER_WALL) ? '-' : ' ';
        $output .= "*{$blow}*{$brow}*\n";

        return $output;
    }
}
