<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Util;
use RuntimeException;
use function fclose;
use function fopen;

final class GenericObject implements DATObject
{
    private DatHeader $header;

    public function __construct(string $filename)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }

        $this->header = DatHeader::fromStream($fp);
        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Flags: {$this->header->flags}");
        Util::printLn("Checksum: {$this->header->checksum}");
    }
}
