<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use GdImage;
use RCTPHP\RCT12\CursorID;
use RCTPHP\RCT2\Object\Enum\PathAdditionDrawType;
use RCTPHP\RCT2\Object\Enum\PathSupportType;
use RCTPHP\Sawyer\ImageHelper;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\WithPreview;
use RCTPHP\Sawyer\SawyerPrice;

class ParkEntranceObject implements RCT2Object, StringTableOwner, ImageTableOwner, WithPreview
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

    public function getPreview(): GdImage
    {
        $preview = ImageHelper::allocatePalettedImage(112, 112);

        ImageHelper::copyImageTableEntry($this->imageTable, 1, $preview, 56 - 32, 56 + 14);
        ImageHelper::copyImageTableEntry($this->imageTable, 0, $preview, 56 + 0, 56 + 28);
        ImageHelper::copyImageTableEntry($this->imageTable, 2, $preview, 56 + 32, 56 + 44);

        return $preview;
    }
}