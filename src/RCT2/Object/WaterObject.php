<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use GdImage;
use RCTPHP\OpenRCT2\Object\WaterObject as OpenRCT2WaterObject;
use RCTPHP\OpenRCT2\Object\WaterPaletteGroup;
use RCTPHP\OpenRCT2\Object\WaterProperties;
use RCTPHP\OpenRCT2\Object\WaterPropertiesPalettes;
use RCTPHP\Sawyer\ImageHelper;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\Object\DATFromFile;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\Object\StringTableDecoder;
use RCTPHP\Sawyer\Object\StringTableOwner;
use RCTPHP\Sawyer\Object\WithPreview;
use RCTPHP\Util;
use RuntimeException;
use Cyndaron\BinaryHandler\BinaryReader;
use function count;
use function json_encode;
use function strlen;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class WaterObject implements RCT2Object, StringTableOwner, ImageTableOwner, ObjectWithOpenRCT2Counterpart, WithPreview
{
    use DATFromFile;
    use StringTableDecoder;

    public bool $allowDucks = true;

    public DATHeader $header;
    /** @var array<string, StringTable> */
    public array $stringTable = [];
    public ImageTable $imageTable;

    public function __construct(DATHeader $header, string $decoded)
    {
        $this->header = $header;
        $reader = BinaryReader::fromString($decoded);

        $reader->seek(14);
        $this->allowDucks = (bool)$reader->readUint16();

        $this->readStringTable($reader, 'name');
        $this->imageTable = new ImageTable($reader->readBytes(strlen($decoded) - $reader->getPosition()));
    }

    public function getPalettes(): WaterPropertiesPalettes
    {
        $parts = $this->imageTable->paletteParts;
        if (count($parts) !== WaterPropertiesPalettes::NUM_PARTS)
        {
            throw new RuntimeException('Incorrect number of palettes!');
        }

        return new WaterPropertiesPalettes([
            WaterPaletteGroup::GENERAL->value => $parts[0],
            WaterPaletteGroup::WAVES_0->value => $parts[1],
            WaterPaletteGroup::WAVES_1->value => $parts[2],
            WaterPaletteGroup::WAVES_2->value => $parts[3],
            WaterPaletteGroup::SPARKLES_0->value => $parts[4],
            WaterPaletteGroup::SPARKLES_1->value => $parts[5],
            WaterPaletteGroup::SPARKLES_2->value => $parts[6],
        ]);
    }

    public function toOpenRCT2Object(): OpenRCT2WaterObject
    {
        $openrct2Object = new OpenRCT2WaterObject(new WaterProperties($this->allowDucks, $this->getPalettes()));
        $openrct2Object->strings = [ 'name' => $this->stringTable['name']->toArray() ];
        $openrct2Object->originalId = $this->header->getAsOriginalId();
        return $openrct2Object;
    }

    public function getImageTable(): ImageTable
    {
        return $this->imageTable;
    }

    public function getPreview(): GdImage
    {
        $preview = imagecreatefrompng(__DIR__ . '/../../../assets/water-preview.png');
        $converted = $this->toOpenRCT2Object();
        ImageHelper::applyPalette($preview, $converted);

        return $preview;
    }
}
