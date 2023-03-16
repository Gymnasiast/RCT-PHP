<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

interface DATObject
{
    /**
     * @param $header
     * @param string $decoded
     */
    public function __construct($header, string $decoded);

    public function printData(): void;

    public static function fromFile(string $filename);
}
