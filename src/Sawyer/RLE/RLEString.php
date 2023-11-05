<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\RLE;

use RuntimeException;
use Stringable;
use function ord;
use function strlen;

final class RLEString implements Stringable
{
    public function __construct(private readonly string $input)
    {
    }

    /**
     * Decode a RLE stream in RCT2’s encoding scheme. Code taken from OpenRCT2.
     *
     * @return string
     */
    public function decode(): string
    {
        $srcLength = strlen($this->input);
        $output = '';

        for ($i = 0; $i < $srcLength; $i++)
        {
            $rleCodeByte = ord($this->input[$i]);
            if ($rleCodeByte & Common::RLE_DUPLICATE)
            {
                $i++;
                $count = 257 - $rleCodeByte;

                if ($i >= $srcLength)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }

                for ($c = 0; $c < $count; $c++)
                {
                    $output .= $this->input[$i];
                }
            }
            else
            {
                if ($i + 1 >= $srcLength)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }
                if ($i + 1 + $rleCodeByte + 1 > $srcLength)
                {
                    throw new RuntimeException('EXCEPTION_MSG_CORRUPT_RLE');
                }

                for ($c = 0; $c < $rleCodeByte + 1; $c++)
                {
                    $output .= $this->input[$i + 1 + $c];
                }

                $i += $rleCodeByte + 1;
            }
        }

        return $output;
    }

    public function getRaw(): string
    {
        return $this->input;
    }

    public function __toString(): string
    {
        return $this->input;
    }

    public function getChecksum(): int
    {
        // TODO: Implement!
        return 0;
    }
}
