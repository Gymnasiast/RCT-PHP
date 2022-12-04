<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\Util;
use function file_put_contents;
use function fread;
use const PHP_EOL;

trait ImageTableDecoder
{
    public function getImageTable()
    {
        return $this->imageTable;
    }

    /**
     * @param resource $fp
     * @return void
     */
    public function readImageTable($fp): void
    {
        $numImages = Bytes::readUint32($fp);
        $imageDataSize = Bytes::readUint32($fp);

        $headerTableSize = $numImages * 16;

        /** @var ImageHeader[] $entries */
        $entries = [];

        for ($i = 0; $i < $numImages; $i++) {
            $entries[] = Bytes::readImageHeader($fp);
        }

        $imageData = fread($fp, $imageDataSize);

        for ($i = 0; $i < $numImages; $i++)
        {
            $currentEntry = $entries[$i];

            $start = $currentEntry->startAddress;
            $end = ($i === $numImages - 1) ? $imageDataSize : $entries[$i + 1]->startAddress;
            $size = $end - $start;

            $dataForThisImage = substr($imageData, $start, $size);
            if ($currentEntry->hasFlag(ImageHeader::FLAG_RLE_COMPRESSION))
            {
                $dataForThisImage = Util::decodeRLE($dataForThisImage);
                echo strlen($dataForThisImage) . PHP_EOL;
            }

            file_put_contents($i . '.bmp', $dataForThisImage);
        }

die();
    }
}
