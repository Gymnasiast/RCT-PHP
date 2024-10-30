<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\Object;

use BackedEnum;
use RCTPHP\Util\Reader\ReadableInterface;
use function array_key_exists;

abstract class DATDetector implements ReadableInterface
{
    public const OBJECT_MAPPING = [];

    protected string $rest;

    public function getObjectType(): BackedEnum
    {
        return $this->getHeader()->getType();
    }

    public function getObject(): DATObject|null
    {
        $type = $this->getObjectType()->value;
        if (!array_key_exists($type, static::OBJECT_MAPPING))
        {
            return null;
        }

        /** @var DATObject $objectType */
        $objectType = static::OBJECT_MAPPING[$type];
        return new $objectType($this->getHeader(), $this->rest);
    }

    abstract public function getHeader(): DATHeader;
}
