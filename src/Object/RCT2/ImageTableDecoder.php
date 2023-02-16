<?php
declare(strict_types=1);

namespace RCTPHP\Object\RCT2;

use RCTPHP\Sawyer\Object\ImageTable;
use RCTPHP\Util;
use function file_put_contents;
use function fread;
use function printf;
use const PHP_EOL;

trait ImageTableDecoder
{
    public function getImageTable(): ImageTable
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
                $dataForThisImage = $this->decodeImageRLE($currentEntry, $dataForThisImage);
            }

            file_put_contents($i . '.bmp', $dataForThisImage);
        }
    }

    private function decodeImageRLE(ImageHeader $header, string $rawData)
    {
        $TZoom = 0;
        $src0 = $rawData; //args.SourceImage.offset;
        $dst0 = '';//args.DestinationBits;
        $srcX = $header->xOffset;//args.SrcX;
        $srcY = $header->yOffset;//args.SrcY;
        $width = $header->width;//args.Width;
        $height = $header->height;//args.Height;
        $zoom = 1 << $TZoom;
        $dstLineWidth = 0;//$width;//(dpi.width >> TZoom) + dpi.pitch;

        // Move up to the first line of the image if source_y_start is negative. Why does this even occur?
    //    if ($srcY < 0)
    //    {
    //        $srcY += $zoom;
    //        $height -= $zoom;
    //        $dst0 += $dstLineWidth;
    //    }

        // For every line in the image
        for ($i = 0; $i < $height; $i += $zoom)
        {
            $y = $srcY + $i;

            // The first part of the source pointer is a list of offsets to different lines
            // This will move the pointer to the correct source line.
            $lineOffset = ord($src0[$y * 2]) | (ord($src0[$y * 2 + 1]) << 8);
            $nextRun = ord($src0[$lineOffset]);
            $dstLineStart = strlen($dst0) + $dstLineWidth * ($i >> $TZoom);

            // Code is goed tot hier!
            printf("y: %d, line offset: %d, next run: %d, dstLineStart: %d\n", $y, $lineOffset, $nextRun, $dstLineStart);

            // For every data chunk in the line
            $isEndOfLine = false;
            while (!$isEndOfLine)
            {
                // Read chunk metadata
                $src = $nextRun;
                printf("src: %u\n", $src);

                $dataSize = $src++;
                $firstPixelX = 0;$src++;//ord($src0[$src++]);

                $isEndOfLine = ($dataSize & 0x80) != 0;
                $dataSize &= 0x7F;

                // Have our next source pointer point to the next data section
                $nextRun = $src + $dataSize;

                $x = $firstPixelX - $srcX;
                $numPixels = $dataSize;
                if ($x > 0)
                {
                    // If x is not a multiple of zoom, round it up to a multiple
                    $mod = $x & ($zoom - 1);
                    if ($mod != 0)
                    {
                        $offset = $zoom - $mod;
                        $x += $offset;
                        $src += $offset;
                        $numPixels -= $offset;
                    }
                }
                else if ($x < 0)
                {
                    // Clamp x to zero if negative
                    $src += -$x;
                    $numPixels += $x;
                    $x = 0;
                }

                // If the end position is further out than the whole image
                // end position then we need to shorten the line again
                $numPixels = min($numPixels, $width - $x);

                $dst = $dstLineStart + ($x >> $TZoom);
    //            if constexpr ((TBlendOp & BLEND_SRC) == 0 && (TBlendOp & BLEND_DST) == 0 && TZoom == 0)
    //            {
    //                // Since we're sampling each pixel at this zoom level, just do a straight std::memcpy
    //                if (numPixels > 0)
    //                {
    //                    std::memcpy(dst, src, numPixels);
    //                }
    //            }
    //            else
                {
                    //$paletteMap = &$args.PalMap;
                    while ($numPixels > 0)
                    {
                        $dst0[$dst] = $src0[$src];
                        //BlitPixel<TBlendOp>(src, dst, paletteMap);
                        $numPixels -= $zoom;
                        $src += $zoom;
                        $dst++;
                    }
                }
            }
        }

        return $dst0;
    }
}
