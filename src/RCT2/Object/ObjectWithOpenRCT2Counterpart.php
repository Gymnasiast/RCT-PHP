<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\OpenRCT2\Object\BaseObject;

interface ObjectWithOpenRCT2Counterpart
{
    public function toOpenRCT2Object(): BaseObject;
}
