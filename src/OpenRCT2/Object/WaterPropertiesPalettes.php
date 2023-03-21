<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use JsonSerializable;
use RCTPHP\Sawyer\ImageTable\Palette;
use RCTPHP\Util\RGB;
use RuntimeException;
use function var_dump;

final class WaterPropertiesPalettes implements JsonSerializable
{
    public const NUM_PARTS = 7;

    /** @var array<string, Palette> */
    private array $parts;

    /**
     * @param array<string, Palette> $parts
     */
    public function __construct(array $parts = [])
    {
        if (empty($parts))
        {
            $this->parts = [
                WaterPaletteGroup::GENERAL->value => new Palette(10, 236),
                WaterPaletteGroup::WAVES_0->value => new Palette(16, 15),
                WaterPaletteGroup::WAVES_1->value => new Palette(32, 15),
                WaterPaletteGroup::WAVES_2->value => new Palette(48, 15),
                WaterPaletteGroup::SPARKLES_0->value => new Palette(80, 15),
                WaterPaletteGroup::SPARKLES_1->value => new Palette(96, 15),
                WaterPaletteGroup::SPARKLES_2->value => new Palette(112, 15),
            ];
        }
        else
        {
            $this->parts = $parts;
        }
    }

    /**
     * @return array<string, Palette>
     */
    public function getParts(): array
    {
        return $this->parts;
    }

    public function setColor(WaterPaletteGroup $paletteGroup, int $index, RGB $color): void
    {
        $this->parts[$paletteGroup->value]->colors[$index] = $color;
    }

    public function setColorByAbsoluteIndex(int $index, RGB $color): void
    {
        $restIndex = $index;
        foreach ($this->parts as $name => $part)
        {
            if ($restIndex < $part->numColors)
            {
                $this->parts[$name]->colors[$restIndex] = $color;
                return;
            }

            $restIndex -= $part->numColors;
        }

        throw new RuntimeException('Index too high!');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        $ret = [];

        foreach ($this->parts as $name => $part)
        {
            $colors = [];
            foreach ($part->colors as $color)
            {
                $colors[] = $color->toHex();
            }


            $ret[$name] = [
                'index' => $part->index,
                'colours' => $colors,
            ];
        }

        return $ret;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
