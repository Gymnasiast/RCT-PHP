<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

interface DATObject
{
    public function printData(): void;

    public static function fromFile(string $filename): self;
}
