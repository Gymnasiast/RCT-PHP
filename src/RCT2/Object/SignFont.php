<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

class SignFont
{
    public function __construct(
        public readonly SignFontOffset $offset0,
        public readonly SignFontOffset $offset1,
        public readonly int $maxWidth,
        public readonly int $flags,
        public readonly int $numImages,
        /** @var SignFontGlyph[] */
        public readonly array $glyphs,
    ) {
    }
}
