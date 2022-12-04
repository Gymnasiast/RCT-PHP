<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\Object\OpenRCT2\BaseObject;

interface ObjectWithOpenRCT2Counterpart
{
    public function toOpenRCT2Object(): BaseObject;
}
