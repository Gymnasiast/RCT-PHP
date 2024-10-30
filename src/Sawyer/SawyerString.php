<?php
namespace RCTPHP\Sawyer;

use JsonSerializable;
use function mb_convert_encoding;
use function strlen;
use function ord;

final class SawyerString implements JsonSerializable
{
    private const MULTIBYTE_MARKER = 0xFF;

    public function __construct(
        public readonly SawyerStringLanguage $language,
        public readonly string $string
    ) {
    }

    public function toUtf8(): string
    {
        $encoding = $this->language->getSourceEncoding();

        $length = strlen($this->string);
        $pos = 0;
        $output = '';
        while ($pos < $length)
        {
            $char = $this->string[$pos];
            if (ord($char) === self::MULTIBYTE_MARKER)
            {
                $char = $this->string[++$pos] . $this->string[++$pos];
            }

            $output .= mb_convert_encoding($char, 'UTF-8', $encoding);


            $pos++;
        }

        return $output;
    }

    public function jsonSerialize(): string
    {
        return $this->toUtf8();
    }
}
