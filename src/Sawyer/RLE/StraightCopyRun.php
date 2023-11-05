<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\RLE;

use function strlen;
use function str_split;
use function chr;

final class StraightCopyRun implements RLERun
{
    public function __construct(public string $bytes)
    {
    }

    public function toBinary(): string
    {
        $output = '';
        $chunks = str_split($this->bytes, Common::RLE_MAX_COPIES);

        foreach ($chunks as $chunk)
        {
            $numBytes = strlen($chunk) - 1;
            $output .= chr($numBytes);
            $output .= $chunk;
        }

        return $output;
    }
}
