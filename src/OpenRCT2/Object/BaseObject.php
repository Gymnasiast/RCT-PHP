<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

use RCTPHP\RCT2\Object\DATHeader;
use RCTPHP\RCT2\Object\SourceGame as RCT2SourceGame;

abstract class BaseObject
{
    public string $id = '';
    /** @var string[] */
    public array $authors = [];
    public string $version = '1.0';
    public string|null $originalId = null;
    /** @var SourceGame[] */
    public array $sourceGame = [SourceGame::CUSTOM];
    public ObjectType $objectType;
    /** @var array<string, mixed> */
    public array $strings = [];

    public function copyDataFromDATHeader(DATHeader $header): void
    {
        $this->originalId = $header->getAsOriginalId();
        $rct2SourceGame = $header->getSourceGame();
        $this->authors = match($rct2SourceGame)
        {
            RCT2SourceGame::RCT2 => ['Chris Sawyer', 'Simon Foster'],
            RCT2SourceGame::WW => ['Frontier Studios'],
            RCT2SourceGame::TT => ['Frontier Studios'],
            RCT2SourceGame::CUSTOM => [],
        };
        $sourceGame = SourceGame::fromRCT2($rct2SourceGame);
        $this->sourceGame = [$sourceGame];
        $name = strtolower($header->name);
        $this->id = "{$sourceGame->value}.{$this->objectType->value}.{$name}";
    }
}
