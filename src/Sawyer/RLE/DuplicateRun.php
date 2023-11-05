<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\RLE;

use function array_fill;
use function ceil;
use function chr;

final class DuplicateRun implements RLERun
{
    public function __construct(public int $numCopies, public string $byte)
    {
    }

    public function toBinary(): string
    {
        $output = '';
        $numChunks = (int)ceil($this->numCopies / Common::RLE_MAX_COPIES);
        $chunkSizes = array_fill(0, $numChunks - 1, Common::RLE_MAX_COPIES);
        $chunkSizes[$numChunks - 1] = $this->numCopies % Common::RLE_MAX_COPIES;

        for ($i = 0; $i < $numChunks; $i++)
        {
            $size = $chunkSizes[$i];
            $lower7 = 129 - $size;
            $rleByte = Common::RLE_DUPLICATE | $lower7;
            $output .= chr($rleByte) . $this->byte;
        }

        return $output;
    }
}
