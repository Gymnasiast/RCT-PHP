<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

final class SceneryGroupProperties
{
    /**
     * @param string[] $entries
     * @param int $priority
     * @param string[] $entertainerCostumes
     */
    public function __construct(
        public array $entries = [],
        public int $priority = 40,
        public array $entertainerCostumes = [],
    ) {
    }
}
