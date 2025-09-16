<?php
declare(strict_types=1);

namespace RCTPHP\RCT2\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\Sawyer\Object\DatDataPrinter;
use RCTPHP\Sawyer\Object\DATDetector;
use RCTPHP\Util;
use RuntimeException;
use function get_class;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

class RCT2DataPrinter extends DatDataPrinter
{
    protected function getDetector(BinaryReader $reader): DATDetector
    {
        return \RCTPHP\RCT2\Object\DATDetector::fromReader($reader);
    }

    public function printObjectSpecificData(): void
    {
        if ($this->object === null)
        {
            return;
        }

        /** @var RCT2Object $object */
        $object = $this->object;

        switch (get_class($object))
        {
            case BannerObject::class:
                Util::printLn("Price: {$object->price->asGBP()}");
                break;
            case LargeSceneryObject::class:
                $attachTo = $object->attachTo ? $object->attachTo->getAsOriginalId() : 'N/A';
                Util::printLn("Price: {$object->price->asGBP()}");
                Util::printLn("Removal price: {$object->removalPrice->asGBP()}");
                Util::printLn("Attaches to: {$attachTo}");
                break;
            case ScenarioTextObject::class:
                $isSixFlags = $object->isSixFlags ? 'yes' : 'no';
                Util::printLn("Six Flags park: {$isSixFlags}");
                break;
            case SceneryGroupObject::class:
                Util::printLn("Priority: {$object->priority}");

                Util::printLn('');
                Util::printLn("Entertainer custumes:");
                foreach ($object->getEntertainerCostumes() as $costume)
                {
                    Util::printLn("  {$costume}");
                }

                Util::printLn('');

                Util::printLn('Objects:');
                Util::printLn('');

                foreach ($object->objects as $objectHeader)
                {
                    Util::printLn($objectHeader->getAsSceneryGroupListEntry());
                }

                Util::printLn('');
                break;
            case SmallSceneryObject::class:
                $attachTo = $object->attachTo ? $object->attachTo->getAsOriginalId() : 'N/A';
                Util::printLn("Height: {$object->height} units ({$object->height->asMetresFormatted()})");
                Util::printLn("Price: {$object->price->asGBP()}");
                Util::printLn("Removal price: {$object->removalPrice->asGBP()}");
                Util::printLn("Attaches to: {$attachTo}");
                break;
            case WallObject::class:
                Util::printLn("Height: {$object->height} units ({$object->height->asMetresFormatted()})");
                Util::printLn("Price: {$object->price->asGBP()}");
                break;
            case WaterObject::class:
                $allowDucks = $object->allowDucks ? 'true' : 'false';
                Util::printLn("Allow ducks: {$allowDucks}");

                Util::printLn("");
                try
                {
                    $palettes = $object->getPalettes();
                    Util::printLn(json_encode($palettes->toArray(), JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
                }
                catch (RuntimeException)
                {
                    Util::printLn("Could not read palette!");
                }
                break;
        }
    }
}
