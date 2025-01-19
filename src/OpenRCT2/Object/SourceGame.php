<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use RCTPHP\RCT2\Object\SourceGame as RCT2SourceGame;

enum SourceGame : string
{
    case OFFICIAL = 'official';
    case RCT1 = 'rct1';
    case AA = 'rct1aa';
    case LL = 'rct1ll';
    case RCT2 = 'rct2';
    case WW = 'rct2ww';
    case TT = 'rct2tt';
    case CUSTOM = 'custom';

    public static function fromRCT2(RCT2SourceGame $rct2sourceGame): self
    {
        return match ($rct2sourceGame)
        {
            RCT2SourceGame::RCT2 => self::RCT2,
            RCT2SourceGame::WW => self::WW,
            RCT2SourceGame::TT => self::TT,
            RCT2SourceGame::CUSTOM => self::CUSTOM,
        };
    }
}