<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use JsonSerializable;
use RCTPHP\Sawyer\SawyerString;

final class StringTable implements JsonSerializable
{
    /** @var SawyerString[] */
    public array $strings = [];

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        $ret = [];
        foreach ($this->strings as $string)
        {
            $languageCode = SawyerString::ISO_MAPPING[$string->languageCode];
            $ret[$languageCode] = $string->toUtf8();
        }

        return $ret;
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
