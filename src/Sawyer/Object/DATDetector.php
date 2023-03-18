<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use RCTPHP\RCT2\Object\DATObject;
use TXweb\BinaryHandler\BinaryReader;
use function array_key_exists;

abstract class DATDetector
{
    public const OBJECT_MAPPING = [];

    protected DATHeader $header;
    protected string $rest;

    abstract public function __construct(BinaryReader $reader);

    public function getObjectType(): int
    {
        return $this->header->getType();
    }

    public function getObject(): DATObject|null
    {
        $type = $this->getObjectType();
        if (!array_key_exists($type, static::OBJECT_MAPPING))
        {
            return null;
        }

        $objectType = static::OBJECT_MAPPING[$type];
        return new $objectType($this->header, $this->rest);
    }
}
