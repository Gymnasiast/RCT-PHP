<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;

trait DATFromFile
{
    public static function fromFile(string $filename): self
    {
        $reader = BinaryReader::fromFile($filename);

        $headerClass = self::HEADER_CLASS;
        $header = $headerClass::fromReader($reader);
        $rest = Util::readChunk($reader);

        return new self($header, $rest);
    }
}
