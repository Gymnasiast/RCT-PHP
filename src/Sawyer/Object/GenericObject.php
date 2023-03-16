<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\Sawyer\Object\DATHeader as GenericDATHeader;
use RCTPHP\Util;

final class GenericObject implements DATObject
{
    private GenericDATHeader $header;

    /**
     * @param GenericDATHeader $header
     * @param string $decoded
     */
    public function __construct($header, string $decoded)
    {
        $this->header = $header;
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Flags: {$this->header->flags}");
        Util::printLn("Checksum: {$this->header->checksum}");
        Util::printLn("Type: {$this->header->getType()}");
    }
}
