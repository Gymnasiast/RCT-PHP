<?php
declare(strict_types=1);

namespace TXweb\BinaryHandler;

use function ftell;

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
