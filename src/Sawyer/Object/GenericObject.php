<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\RCT2\Object\DATObject;
use RCTPHP\Sawyer\Object\DATHeader as GenericDATHeader;
use RCTPHP\Util;
use function fseek;

final class GenericObject implements DATObject
{
    private GenericDATHeader $header;

    /**
     * @param GenericDATHeader $header
     * @param resource $stream
     * @param int $filesize
     */
    public function __construct($header, $stream, int $filesize)
    {
        $this->header = $header;
        fseek($stream, DATHeader::DAT_HEADER_SIZE);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Flags: {$this->header->flags}");
        Util::printLn("Checksum: {$this->header->checksum}");
    }
}
