<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;

interface ImageTableOwner
{
    public function getImageTable(): ImageTable;
}
