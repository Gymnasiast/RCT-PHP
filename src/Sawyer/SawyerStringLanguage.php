<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer;

enum SawyerStringLanguage : int
{
    case EN_GB = 0;
    case EN_US = 1;
    case FR_FR = 2;
    case DE_DE = 3;
    case ES_ES = 4;
    case IT_IT = 5;
    case NL_NL = 6;
    case SV_SE = 7;
    case JA_JP = 8;
    case KO_KR = 9;
    case ZH_CN = 10;
    case ZH_TW = 11;
    case PL_PL = 12;
    case PT_BR = 13;

    private const ISO_MAPPING = [
        'en-GB',
        'en-US',
        'fr-FR',
        'de-DE',
        'es-ES',
        'it-IT',
        'nl-NL',
        'sv-SE',
        'ja-JP',
        'ko-KR',
        'zh-CN',
        'zh-TW',
        'pl-PL',
        'pt-BR',
    ];

    public function getIsoCode(): string
    {
        return self::ISO_MAPPING[$this->value];
    }

    public function getSourceEncoding(): string
    {
        return match ($this)
        {
            self::JA_JP => 'SJIS',
            self::KO_KR => 'CP949',
            self::ZH_CN => 'GB2312',
            self::ZH_TW => 'BIG-5',
            default => 'Windows-1252',
        };
    }
}
