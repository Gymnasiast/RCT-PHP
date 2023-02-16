<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use function file_put_contents;

final class ImageTable
{
    public function __construct(public readonly string $binaryData)
    {
    }

    public function exportToFile(string $filename)
    {
        file_put_contents($filename, $this->binaryData);
    }
}
