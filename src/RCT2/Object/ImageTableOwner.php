<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\Object\ImageTable;

interface ImageTableOwner
{
    public function getImageTable(): ImageTable;
}
