<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT12\CursorID;
use RCTPHP\RCT2\Object\Enum\PathAdditionDrawType;
use RCTPHP\RCT2\Object\Enum\PathSupportType;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\SawyerPrice;

class ParkEntranceObject implements RCT2Object, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    private DATHeader $header;

    public int $scrollingMode;
    public int $textHeight;

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

        $reader->seek(6);
        $this->scrollingMode = $reader->readUint8();
        $this->textHeight = $reader->readUint8();

        $this->readStringTable($reader, 'name');
        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));
    }
}