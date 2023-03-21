<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use JsonSerializable;

final class WaterProperties implements JsonSerializable
{
    public function __construct(
        public bool $allowDucks,
        public WaterPropertiesPalettes $palettes = new WaterPropertiesPalettes(),
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'allowDucks' => $this->allowDucks,
            'palettes' => $this->palettes->jsonSerialize(),
        ];
    }
}
