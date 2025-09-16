<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use GdImage;
use RCTPHP\RCT2\Object\Enum\PathSupportType;
use RCTPHP\Sawyer\ImageHelper;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\WithPreview;

class PathObject implements RCT2Object, StringTableOwner, ImageTableOwner, WithPreview
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

    public function getPreview(): GdImage
    {
        $preview = ImageHelper::allocatePalettedImage(112, 112);

        ImageHelper::copyImageTableEntry($this->imageTable, 71, $preview, 56 - 49, 56 - 17);
        ImageHelper::copyImageTableEntry($this->imageTable, 72, $preview, 56 + 4, 56 - 17);

        return $preview;
    }
}