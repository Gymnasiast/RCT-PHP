<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\SawyerPrice;
use RCTPHP\Sawyer\SawyerTileHeight;
use Cyndaron\BinaryHandler\BinaryReader;
use function strlen;

class SmallSceneryObject implements RCT2Object, StringTableOwner, ImageTableOwner
{
    use DATFromFile;
    use StringTableDecoder;

    public readonly DATHeader $header;

    /** @var StringTable[] */
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
}
