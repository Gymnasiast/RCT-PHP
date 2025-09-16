<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use GdImage;
use RCTPHP\Sawyer\ImageHelper;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\WithPreview;
use RCTPHP\Sawyer\SawyerPrice;

class BannerObject implements RCT2Object, StringTableOwner, ImageTableOwner, WithPreview
{
    use DATFromFile;
    use StringTableDecoder;

    private DATHeader $header;
    public int $scrollingMode;
    public int $flags;
    public readonly SawyerPrice $price;

    /** @var array<string, StringTable> */
    public array $stringTable = [];

    public readonly DATHeader|null $attachTo;

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
        $this->flags = $reader->readUint8();
        $this->price = new SawyerPrice($reader->readSint16());
        $reader->seek(2);

        $this->readStringTable($reader, 'name');

        $attachTo = DATHeader::tryFromReader($reader);
        $this->attachTo = ($attachTo !== null && !$attachTo->isBlank()) ? $attachTo : null;

        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));

    }

    public function getPreview(): GdImage
    {
        $preview = ImageHelper::allocatePalettedImage(112, 112);

        // TODO: implement remap support
        $image0 = $this->imageTable->gdImageData[0];
        $image1 = $this->imageTable->gdImageData[1];

        ImageHelper::copyImage($image0, $preview, 56 - 12, 56 + 8);
        ImageHelper::copyImage($image1, $preview, 56 - 12, 56 + 8);

        return $preview;
    }
}
