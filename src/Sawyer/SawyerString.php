<?php
namespace RCTPHP\Sawyer;

use JsonSerializable;
use function mb_convert_encoding;
use function strlen;
use function ord;

final class SawyerString implements JsonSerializable
{
    public const EN_GB = 0;
    public const EN_US = 1;
    public const FR_FR = 2;
    public const DE_DE = 3;
    public const ES_ES = 4;
    public const IT_IT = 5;
    public const NL_NL = 6;
    public const SV_SE = 7;
    public const JA_JP = 8;
    public const KO_KR = 9;
    public const ZH_CN = 10;
    public const ZH_TW = 11;
    public const PL_PL = 12;
    public const PT_BR = 13;

    public const ISO_MAPPING = [
        self::EN_GB => 'en-GB',
        self::EN_US => 'en-US',
        self::FR_FR => 'fr-FR',
        self::DE_DE => 'de-DE',
        self::ES_ES => 'es-ES',
        self::IT_IT => 'it-IT',
        self::NL_NL => 'nl-NL',
        self::SV_SE => 'sv-SE',
        self::JA_JP => 'ja-JP',
        self::KO_KR => 'ko-KR',
        self::ZH_CN => 'zh-CN',
        self::ZH_TW => 'zh-TW',
        self::PL_PL => 'pl-PL',
        self::PT_BR => 'pt-BR',
    ];

    private const MULTIBYTE_MARKER = 0xFF;

    public int $languageCode;
    public string $string;

    public function __construct(int $languageCode, string $string)
    {
        $this->languageCode = $languageCode;
        $this->string = $string;
    }

    public static function getSourceEncoding(int $languageCode): string
    {
        return match ($languageCode)
        {
            self::JA_JP => 'SJIS',
            self::KO_KR => 'CP949',
            self::ZH_CN => 'GB2312',
            self::ZH_TW => 'BIG-5',
            default => 'Windows-1252',
        };
    }

    public function toUtf8(): string
    {
        $encoding = self::getSourceEncoding($this->languageCode);

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
