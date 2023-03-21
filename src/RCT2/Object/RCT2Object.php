<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

interface RCT2Object extends \RCTPHP\Sawyer\Object\DATObject
{
    public const HEADER_CLASS = DATHeader::class;

    /**
     * @param DATHeader $header
     * @param string $decoded
     */
    public function __construct(DATHeader $header, string $decoded);
}
