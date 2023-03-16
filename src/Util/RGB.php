<?php
declare(strict_types=1);

namespace RCTPHP\Util;

use JsonSerializable;
use function dechex;
use function str_pad;
use function strtoupper;
use const STR_PAD_LEFT;

final class RGB implements JsonSerializable
{
    public function __construct(
        public readonly int $r,
        public readonly int $g,
        public readonly int $b,
    )
    {}

    public function toHex(): string
    {
        $rHex = str_pad(strtoupper(dechex($this->r)), 2, '0', STR_PAD_LEFT);
        $gHex = str_pad(strtoupper(dechex($this->g)), 2, '0', STR_PAD_LEFT);
        $bHex = str_pad(strtoupper(dechex($this->b)), 2, '0', STR_PAD_LEFT);

        return "#{$rHex}{$gHex}{$bHex}";
    }

    public function jsonSerialize(): string
    {
        return $this->toHex();
    }
}