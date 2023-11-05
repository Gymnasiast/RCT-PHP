<?php
declare(strict_types=1);

namespace RCTPHP\Sawyer\RLE;

use function strlen;

final class NonRLEString
{
    public function __construct(private readonly string $input)
    {
    }

    public function encode(): RLEString
    {
        $inputLength = strlen($this->input);
        /** @var RLERun $runs */
        $runs = [];
        $buffer = '';

        for ($i = 0; $i < $inputLength; $i++)
        {
            $currentByte = $this->input[$i];
            $numSameBytes = 1;
            while ($i < $inputLength - 1)
            {
                $nextByte = $this->input[$i + 1];
                if ($nextByte !== $currentByte)
                {
                    break;
                }

                $numSameBytes++;
                $i++;
            }

            if ($numSameBytes > 1)
            {
                $runs[] = new StraightCopyRun($buffer);
                $buffer = '';

                $runs[] = new DuplicateRun($numSameBytes, $currentByte);
            }
            else
            {
                $buffer .= $currentByte;
            }
        }

        if ($buffer !== '')
        {
            $runs[] = new StraightCopyRun($buffer);
        }

        $output = '';
        foreach ($runs as $run)
        {
            $output .= $run->toBinary();
        }

        return new RLEString($output);
    }

    public function getRaw(): string
    {
        return $this->input;
    }

    public function __toString(): string
    {
        return $this->input;
    }
}
