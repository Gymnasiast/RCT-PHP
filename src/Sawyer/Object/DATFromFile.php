<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\Locomotion\Object\DATHeader as LocoDATHeader;
use RCTPHP\Locomotion\Object\LocomotionObject;
use RCTPHP\RCT2\Object\DATHeader as RCT2DATHeader;
use RCTPHP\Util;
use Cyndaron\BinaryHandler\BinaryReader;

trait DATFromFile
{
    public static function fromFile(string $filename)
    {
        $reader = BinaryReader::fromFile($filename);

        if (self::class instanceof LocomotionObject)
        {
            $header = new LocoDATHeader($reader);
        }
        else
        {
            $header = new RCT2DATHeader($reader);
        }

        $rest = Util::readChunk($reader);

        return new self($header, $rest);
    }
}
