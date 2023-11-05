<?php
declare(strict_types=1);

namespace RCTPHP\Util\PNG;

use function count;
use function unpack;
use function substr;
use function pack;

final class Animated
{
    /** @var Chunk[] */
    public array $chunks;

    /**
     * @param File[] $files
     */
    public function __construct(array $files)
    {
        $ihdr = null;
        $plte = null;
        foreach ($files[0]->chunks as $chunk)
        {
            if ($chunk->code === 'IHDR')
            {
                $ihdr = $chunk;
            }
            elseif ($chunk->code === 'PLTE')
            {
                $plte = $chunk;
            }
            if ($ihdr !== null && $plte !== null)
            {
                break;
            }
        }
        if ($ihdr === null)
        {
            throw new \RuntimeException('Could not find an IHDR struct!');
        }

        $numFiles = count($files);

        $this->chunks = [
            $ihdr,
            new Chunk(8, 'acTL', pack('N', $numFiles) . pack('N', 0)),
        ];
        if ($plte !== null)
        {
            $this->chunks[] = $plte;
        }

        $width = (int)(unpack('N', substr($ihdr->data, 0, 4))[1]);
        $height = (int)(unpack('N', substr($ihdr->data, 4, 4))[1]);

        $sequenceNum = 0;
        foreach ($files as $fileNum => $file)
        {
            $this->chunks[] = new fcTL(
                $sequenceNum++,
                $width,
                $height,
                0,
                0,
                1,
                100,
                0,
                0,
            );

            foreach ($file->chunks as $chunk)
            {
                if ($chunk->code === 'IDAT')
                {
                    if ($fileNum === 0)
                    {
                        $this->chunks[] = $chunk;
                    }
                    else
                    {
                        $copy = clone $chunk;
                        $copy->length += 4;
                        $copy->code = 'fdAT';
                        $copy->data = pack('N', $sequenceNum++) . $copy->data;
                        $copy->updateCrc();
                        $this->chunks[] = $copy;
                    }
                }
            }
        }

        $this->chunks[] = new Chunk(0, 'IEND', '');
    }

    public function getFile(): File
    {
        return new File($this->chunks);
    }
}
