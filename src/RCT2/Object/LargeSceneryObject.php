<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

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
use RCTPHP\Sawyer\SawyerTileHeight;
use Cyndaron\BinaryHandler\BinaryReader;
use function strlen;

class LargeSceneryObject implements RCT2Object, StringTableOwner, ImageTableOwner, WithPreview
{
    use DATFromFile;
    use StringTableDecoder;

    public readonly DATHeader $header;

    /** @var array<string, StringTable> */
    public array $stringTable = [];

    public readonly DATHeader|null $attachTo;

    public readonly ImageTable $imageTable;
    public readonly int $toolId;
    public readonly int $flags;
    public readonly SawyerPrice $price;
    public readonly SawyerPrice $removalPrice;
    public readonly int $scrollingMode;
    public readonly SignFont $font;
    /** @var LargeSceneryTile[] */
    public readonly array $tiles;

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(6);
        $this->toolId = $reader->readUint8();
        $this->flags = $reader->readUint8();
        $this->price = new SawyerPrice($reader->readSint16() * 10);
        $this->removalPrice = new SawyerPrice($reader->readSint16() * 10);
        $reader->seek(5);
        $this->scrollingMode = $reader->readUint8();

        $reader->seek(4);

        $this->readStringTable($reader, 'name');

        $attachTo = DATHeader::tryFromReader($reader);
        $this->attachTo = ($attachTo !== null && !$attachTo->isBlank()) ? $attachTo : null;

        if ($this->flags & (1 << 2))
        {
            $this->font = $this->read3DFont($reader);
        }
        $this->tiles = $this->readTiles($reader);

        $imageTableSize = strlen($decoded) - $reader->getPosition();
        $imageTable = $reader->readBytes($imageTableSize);
        $this->imageTable = new ImageTable($imageTable);
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }

    private function read3DFont(BinaryReader $reader): SignFont
    {
        $x0 = $reader->readSint16();
        $y0 = $reader->readSint16();
        $offset0 = new SignFontOffset($x0, $y0);
        $x1 = $reader->readSint16();
        $y1 = $reader->readSint16();
        $offset1 = new SignFontOffset($x1, $y1);
        $maxWidth = $reader->readUint16();
        $reader->seek(2);
        $flags = $reader->readUint8();
        $numImages = $reader->readUint8();

        $glyphs = [];
        for ($i = 0; $i < 256; $i++)
        {
            $imageOffset = $reader->readUint8();
            $width = $reader->readUint8();
            $height = $reader->readUint8();
            $reader->seek(1);
            $glyphs[] = new SignFontGlyph($imageOffset, $width, $height);
        }

        return new SignFont($offset0, $offset1, $maxWidth, $flags, $numImages, $glyphs);
    }

    /**
     * @param BinaryReader $reader
     * @return LargeSceneryTile[]
     */
    private function readTiles(BinaryReader $reader): array
    {
        $tiles = [];
        while (true)
        {
            $peek = $reader->readUint16();
            if ($peek === 0xFFFF)
            {
                break;
            }
            $reader->seek(-2);


            $xOffset = $reader->readSint16();
            $yOffset = $reader->readSint16();
            $zOffset = $reader->readSint16();
            $zClearance = $reader->readUint8();
            $flags = $reader->readUint16();

            $tiles[] = new LargeSceneryTile($xOffset, $yOffset, $zOffset, $zClearance, $flags);
        }

        return $tiles;
    }

    public function getPreview(): GdImage
    {
        $preview = ImageHelper::allocatePalettedImage(112, 112);
        // RCT2 would always remap large scenery, regardless of flags
        ImageHelper::setPrimaryRemap($preview, 57);
        ImageHelper::setSecondaryRemap($preview, 45);

        ImageHelper::copyImageTableEntry($this->imageTable, 0, $preview, 56, 56 - 39);

        return $preview;
    }
}
