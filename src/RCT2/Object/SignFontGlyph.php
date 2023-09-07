<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

class SignFontGlyph
{
    public function __construct(
        public readonly int $imageOffset,
        public readonly int $width,
        public readonly int $height,
    )
    {
    }
}
