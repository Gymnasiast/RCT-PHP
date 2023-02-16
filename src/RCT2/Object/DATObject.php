<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Locomotion\Object\DATHeader as LocoDATHeader;
use RCTPHP\RCT2\Object\DATHeader as RCT2DATHeader;

interface DATObject
{
    /**
     * @param $header
     * @param string $decoded
     */
    public function __construct($header, string $decoded);

    public function printData(): void;
}
