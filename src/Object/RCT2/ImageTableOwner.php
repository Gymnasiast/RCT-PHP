<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\Sawyer\Object\ImageTable;

interface ImageTableOwner
{
    public function getImageTable(): ImageTable;
}
