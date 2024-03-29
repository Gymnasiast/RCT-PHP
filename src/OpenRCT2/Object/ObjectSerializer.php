<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use function array_key_exists;
use function json_decode;
use function json_encode;
use const JSON_PRETTY_PRINT;
use const JSON_THROW_ON_ERROR;
use function str_replace;

final class ObjectSerializer
{
    private BaseObject $object;

    public function __construct(BaseObject $object)
    {
        $this->object = $object;
    }

    /**
     * @throws \JsonException
     * @return array<string, mixed>
     */
    public function serializeToArray(): array
    {
        $firstPass = json_encode($this->object, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        /** @var array<string, mixed> $vars */
        $vars = json_decode($firstPass, true, 512, JSON_THROW_ON_ERROR);

        if (array_key_exists('originalId', $vars) && empty($vars['originalId']))
        {
            unset($vars['originalId']);
        }

        // Ensure the object _ends_ with the string table. Change _ to -.
        $strings = $vars['strings'];
        unset($vars['strings']);
        $out = [];
        foreach ($vars as $key => $value)
        {
            $key = str_replace('_', '-', $key);
            $out[$key] = $value;
        }

        $out['strings'] = $strings;

        return $out;
    }

    public function serializeToJson(): string
    {
        $contents = $this->serializeToArray();
        return json_encode($contents, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
