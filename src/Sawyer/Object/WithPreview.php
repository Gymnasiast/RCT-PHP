<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use GdImage;

interface WithPreview
{
    public function getPreview(): GdImage;
}
