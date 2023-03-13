<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use RCTPHP\Binary;
use RCTPHP\OpenRCT2\Object\WaterObject as OpenRCT2WaterObject;
use RCTPHP\OpenRCT2\Object\WaterPaletteGroup;
use RCTPHP\OpenRCT2\Object\WaterProperties;
use RCTPHP\OpenRCT2\Object\WaterPropertiesPalettes;
use RCTPHP\Sawyer\ImageTable\ImageTable;
use RCTPHP\Sawyer\ImageTable\ImageTableDecoder;
use RCTPHP\Sawyer\Object\ImageTableOwner;
use RCTPHP\Sawyer\Object\StringTable;
use RCTPHP\Sawyer\SawyerString;
use RCTPHP\Util;
use RuntimeException;
use function count;
use function fclose;
use function fopen;
use function fread;
use function fseek;
use function ftell;
use function fwrite;
use function json_encode;
use function rewind;
use function strlen;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use const SEEK_CUR;

class WaterObject implements DATObject, StringTableOwner, ImageTableOwner, ObjectWithOpenRCT2Counterpart
{
    use ImageTableDecoder;
    use StringTableDecoder;

    public bool $allowDucks = true;

    public DATHeader $header;
    /** @var StringTable[] */
    public array $stringTable = [];
    public ImageTable $imageTable;

    public function __construct($header, string $decoded)
    {
        $this->header = $header;
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $decoded);

        rewind($fp);
        fseek($fp, 14, SEEK_CUR);
        $this->allowDucks = (bool)Binary::readUint16($fp);

        $this->readStringTable($fp, 'name');
        $this->imageTable = new ImageTable(fread($fp, strlen($decoded) - ftell($fp)));

        fclose($fp);
    }

    public function printData(): void
    {
        $allowDucks = $this->allowDucks ? 'true' : 'false';
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Allow ducks: {$allowDucks}");

        $this->printStringTables();
        Util::printLn("");
        try
        {
            $palettes = $this->getPalettes();
            Util::printLn(json_encode($palettes->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
        }
        catch (RuntimeException)
        {
            Util::printLn("Could not read palette!");
        }
    }

    private function getPalettes(): WaterPropertiesPalettes
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
}
