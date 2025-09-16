<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use GdImage;
use RCTPHP\OpenRCT2\Object\BaseObject;
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

class SmallSceneryObject implements RCT2Object, StringTableOwner, ImageTableOwner, ObjectWithOpenRCT2Counterpart, WithPreview
{
    use DATFromFile;
    use StringTableDecoder;

    public const SMALL_SCENERY_FLAG_FULL_TILE = (1 << 0);
    public const SMALL_SCENERY_FLAG_VOFFSET_CENTER = (1 << 1);

    public readonly DATHeader $header;

    /** @var array<string, StringTable> */
    public array $stringTable = [];

    public readonly DATHeader|null $attachTo;

    public readonly ImageTable $imageTable;
    public readonly int $flags;
    public readonly SawyerTileHeight $height;
    public readonly int $toolId;
    public readonly SawyerPrice $price;
    public readonly SawyerPrice $removalPrice;
    public readonly int $animationDelay;
    public readonly int $animationMask;
    public readonly int $numFrames;
    /** @var int[] */
    public readonly array $frameOffsets;

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(6);
        $this->flags = $reader->readUint32();
        $this->height = new SawyerTileHeight($reader->readUint8() >> 3);
        $this->toolId = $reader->readUint8();
        $this->price = new SawyerPrice($reader->readSint16() * 10);
        $this->removalPrice = new SawyerPrice($reader->readSint16() * 10);
        $reader->seek(4);
        $this->animationDelay = $reader->readUint16();
        $this->animationMask = $reader->readUint16();
        $this->numFrames = $reader->readUint16();

        $this->readStringTable($reader, 'name');

        $attachTo = DATHeader::tryFromReader($reader);
        $this->attachTo = ($attachTo !== null && !$attachTo->isBlank()) ? $attachTo : null;

        $frameOffsets = [];
        if ($this->flags & (1 << 15))
        {
            $frameOffsets = $this->readFrameOffsets($reader);
        }

        $this->frameOffsets = $frameOffsets;

        $imageTableSize = strlen($decoded) - $reader->getPosition();
        $imageTable = $reader->readBytes($imageTableSize);
        $this->imageTable = new ImageTable($imageTable);
    }

    /**
     * @return int[]
     */
    private function readFrameOffsets(BinaryReader $reader): array
    {
        $frameOffsets = [];
        while (($frameOffset = $reader->readUint8()) !== 0xFF)
        {
            $frameOffsets[] = $frameOffset;
        }

        return $frameOffsets;
    }

    public function toOpenRCT2Object(): \RCTPHP\OpenRCT2\Object\SmallSceneryObject
    {
        $ret = new \RCTPHP\OpenRCT2\Object\SmallSceneryObject();
        $ret->images = $this->imageTable;
        $ret->strings = ['name' => $this->stringTable['name']->toArray()];
        return $ret;
    }

    public function getPreview(): GdImage
    {
        $preview = ImageHelper::allocatePalettedImage(112, 112);

        $y = 56 + (int)($this->height->internal / 2);
//        $y = min($y, $this->height->internal - 16);
        if (($this->flags & self::SMALL_SCENERY_FLAG_FULL_TILE) && ($this->flags & self::SMALL_SCENERY_FLAG_VOFFSET_CENTER))
        {
            $y -= 12;
        }

        ImageHelper::copyImageTableEntry($this->imageTable, 0, $preview, 56, $y);

        return $preview;
    }
}
