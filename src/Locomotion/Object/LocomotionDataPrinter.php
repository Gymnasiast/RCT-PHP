<?php
declare(strict_types=1);

namespace RCTPHP\Locomotion\Object;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\Sawyer\Object\DatDataPrinter;
use RCTPHP\Sawyer\Object\DATDetector;
use RCTPHP\Util;
use function array_map;
use function get_class;
use function implode;

class LocomotionDataPrinter extends DatDataPrinter
{
    protected function getDetector(BinaryReader $reader): DATDetector
    {
        return new \RCTPHP\Locomotion\Object\DATDetector($reader);
    }

    public function printObjectSpecificData(): void
    {
        if ($this->object === null)
        {
            return;
        }

        /** @var LocomotionObject $object */
        $object = $this->object;

        switch (get_class($object))
        {
            case SoundObject::class:
                Util::printLn("Volume: {$object->volume}");
                break;
            case TrackObject::class:
                $lists = [
                    'Compatible tracks/roads' => &$object->compatibleTracksRoads,
                    'Mods' => &$object->mods,
                    'Signals' => &$object->signals,
                    'Bridges' => &$object->bridges,
                    'Stations' => &$object->stations,
                ];
                foreach ($lists as $key => $list)
                {
                    $imploded = implode(', ', array_map(static function(DATHeader $header)
                    { return $header->name; }, $list));
                    Util::printLn("$key: {$imploded}");
                }

                Util::printLn('Tunnel: ' . ($object->tunnel ? $object->tunnel->name : 'N/A'));
                break;
        }
    }
}
