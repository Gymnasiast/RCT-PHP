<?php
declare(strict_types=1);

namespace RCTPHP\Util\PNG;

use function crc32;

class Chunk
{
    public int $length;
    public string $code;
    public string $data;
    public int $crc;

    public function __construct(int $length, string $code, string $data, int $crc = null)
    {
        $this->length = $length;
        $this->code = $code;
        $this->data = $data;
        if ($crc === null)
        {
            $this->updateCrc();
        }
        else
        {
            $this->crc = $crc;
        }
    }

    public function updateCrc(): void
    {
        $this->crc = crc32($this->code . $this->data);
    }
}
