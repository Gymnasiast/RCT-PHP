<?php
declare(strict_types=1);

namespace RCTPHP\OpenRCT2\Object;

abstract class BaseObject
{
    public string $id = '';
    /** @var string[] */
    public array $authors = [];
    public string $version = '1.0';
    public string|null $originalId = null;
    public string $sourceGame = 'custom';
    public ObjectType $objectType;
    public array $strings = [];


}
