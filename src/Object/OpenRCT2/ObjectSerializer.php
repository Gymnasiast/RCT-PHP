<?php
declare(strict_types=1);

namespace RCTPHP\Object\OpenRCT2;

use function json_decode;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;

final class ObjectSerializer
{
    private BaseObject $object;

    public function __construct(BaseObject $object)
    {
        $this->object = $object;
    }

    public function serializeToArray(): array
    {
        $firstPass = json_encode($this->object, JSON_THROW_ON_ERROR);
        $vars = json_decode($firstPass, true, 512, JSON_THROW_ON_ERROR);

        // Ensure the object _ends_ with the string table.
        $strings = $vars['strings'];
        unset($vars['strings']);
        $vars['strings'] = $strings;

        return $vars;
    }

    public function serializeToJson(): string
    {
        $contents = $this->serializeToArray();
        return json_encode($contents, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
    }
}
