<?php
namespace RCTPHP\Sawyer\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\OpenRCT2\Object\ObjectSerializer;
use RCTPHP\RCT2\Object\ObjectWithOpenRCT2Counterpart;
use RCTPHP\Util;
use function file_exists;
use function file_put_contents;
use function mkdir;
use function trim;
use function imagepng;

abstract class DatDataPrinter
{
    protected readonly DATHeader $header;
    protected readonly DATObject|null $object;

    public function __construct(string $filename, protected readonly bool $isDebug = false)
    {
        $reader = BinaryReader::fromFile($filename);
        $detector = $this->getDetector($reader);

        $this->header = $detector->getHeader();
        $this->object = $detector->getObject();
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Flags: {$this->header->flags}");
        Util::printLn("Checksum: {$this->header->checksum}");
        Util::printLn("Type: {$this->header->getType()->name}");
        if ($this->object === null)
        {
            Util::printLn('Onbekend type!');
            return;
        }

        $this->printObjectSpecificData();
        $this->printStringTables();
        $this->printImageTable();
        $this->printPreview();

        if ($this->object instanceof ObjectWithOpenRCT2Counterpart)
        {
            $converted = $this->object->toOpenRCT2Object();
            $serialized = (new ObjectSerializer($converted))->serializeToJson();
            file_put_contents('converted.json', $serialized);
        }
    }

    abstract protected function getDetector(BinaryReader $reader): DATDetector;

    abstract public function printObjectSpecificData(): void;

    public function printStringTables(): void
    {
        if (!$this->object instanceof StringTableOwner)
        {
            return;
        }

        $tables = $this->object->getStringTables();
        foreach ($tables as $name => $stringTable)
        {
            Util::printLn("String table “{$name}”:");
            foreach ($stringTable->strings as $stringTableItem)
            {
                $language = $stringTableItem->language;
                Util::printLn("In-game name {$language->value} ({$language->name}): {$stringTableItem->toUtf8()}");
            }
        }
    }

    public function printImageTable(): void
    {
        if (!$this->isDebug || !$this->object instanceof ImageTableOwner)
        {
            return;
        }

        $imageTable = $this->object->getImageTable();
//        foreach ($imageTable->entries as $entry)
//        {
//            var_dump($entry);
//        }

        $name = trim($this->header->name);
        $exportDir = __DIR__ . "/../../../export/{$name}";
        $imageTable->exportToFile("{$exportDir}/imagetable.dat");
        $exportDir2 = "{$exportDir}/images";
        if (!file_exists($exportDir2))
        {
            mkdir($exportDir2);
        }
        foreach ($imageTable->gdImageData as $index => $image)
        {
            $filename = "{$exportDir2}/{$index}.png";
            imagepng($image, $filename);
        }
    }

    public function printPreview(): void
    {
        if (!$this->isDebug || !($this->object instanceof WithPreview))
        {
            return;
        }

        $gdData = $this->object->getPreview();

        $name = trim($this->header->name);
        $exportDir = __DIR__ . "/../../../export/{$name}";
        if (!file_exists($exportDir))
        {
            mkdir($exportDir, recursive: true);
        }

        $filename = "{$exportDir}/preview.png";
        imagepng($gdData, $filename);
    }
}
