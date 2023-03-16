<?php
declare(strict_types=1);

namespace TXweb\BinaryHandler;

use RuntimeException;
use function fseek;
use function ftell;
use function is_resource;
use const SEEK_SET;

abstract class BinaryHandler
{
    /** @var resource */
    protected $fp;
    protected bool $closeOnDescruction;

    /**
     * @param resource $fp
     */
    public function __construct($fp, bool $closeOnDestruction = false)
    {
        if (!is_resource($fp))
        {
            throw new RuntimeException('$fp must be a resource!');
        }
        $this->fp = $fp;
        $this->closeOnDescruction = $closeOnDestruction;
    }

    public function __destruct()
    {
        if ($this->closeOnDescruction)
        {
            fclose($this->fp);
        }
    }

    public function rewind(): void
    {
        rewind($this->fp);
    }

    public function seek(int $bytes): void
    {
        fseek($this->fp, $bytes, SEEK_CUR);
    }

    public function moveTo(int $position): void
    {
        fseek($this->fp, $position, SEEK_SET);
    }

    public function getSize(): int
    {
        return (int)(fstat($this->fp)['size']);
    }

    public function getPosition(): int
    {
        return ftell($this->fp);
    }

    public static function fromString(string $input): static
    {
        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $input);
        rewind($fp);
        return new static($fp, true);
    }

    abstract public static function fromFile(string $filename): static;
}
