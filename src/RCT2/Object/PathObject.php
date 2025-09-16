<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT2\Object\Enum\PathSupportType;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;

class PathObject implements RCT2Object, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    private DATHeader $header;

    private PathSupportType $supportType;
    private int $flags;
    private int $scrollingMode;

    /** @var array<string, StringTable> */
    public array $stringTable = [];
    private ImageTable $imageTable;

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(10);
        $this->supportType = PathSupportType::from($reader->readUint8());
        $this->flags = $reader->readUint8();
        $this->scrollingMode = $reader->readUint8();
        $reader->seek(1);

        $this->readStringTable($reader, 'name');
        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));

    }
}