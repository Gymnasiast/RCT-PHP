<?php
declare(strict_types=1);

namespace RCTPHP\Util\PCX;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use RCTPHP\Util\Reader\ReadableInterface;
use RCTPHP\Util\Reader\TryFromReaderTrait;

final class PCXHeader implements ReadableInterface
{
    use TryFromReaderTrait;

    public function __construct(
        public readonly int $manufacturer,
        public readonly int $version,
        public readonly int $encoding,
        public readonly int $bitsPerPixel,
        public readonly int $imgXMin,
        public readonly int $imgYMin,
        public readonly int $imgXMax,
        public readonly int $imgYMax,
        public readonly int $hdpi,
        public readonly int $vdpi,
        public readonly string $palette16,
        public readonly int $reserved,
        public readonly int $numPlanes,
        public readonly int $bytesPerLine,
        public readonly int $paletteInfo,
        public readonly int $hScreenSize,
        public readonly int $vScreenSize,
        public readonly string $reserved2,
    ) {
    }

    public function getWidth(): int
    {
        return $this->imgXMax - $this->imgXMin + 1;
    }

    public function getHeight(): int
    {
        return $this->imgYMax - $this->imgYMin + 1;
    }

    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): self
    {
        return new self(
            manufacturer: $reader->readUint8(), // 0x00
            version: $reader->readUint8(), // 0x01
            encoding: $reader->readUint8(), // 0x02
            bitsPerPixel: $reader->readUint8(), // 0x03
            imgXMin: $reader->readUint16(), // 0x04
            imgYMin: $reader->readUint16(), // 0x06
            imgXMax: $reader->readUint16(), // 0x08
            imgYMax: $reader->readUint16(), // 0x0A
            hdpi: $reader->readUint16(), // 0x0C
            vdpi: $reader->readUint16(), // 0x0E
            palette16: $reader->readBytes(48), // 0x10
            reserved: $reader->readUint8(), // 0x40
            numPlanes: $reader->readUint8(), // 0x41
            bytesPerLine: $reader->readUint16(), // 0x42
            paletteInfo: $reader->readUint16(), // 0x44
            hScreenSize: $reader->readUint16(), // 0x46
            vScreenSize: $reader->readUint16(), // 0x48
            reserved2: $reader->readBytes(54), // 0x4A
        );
    }

    public function getVersion(): PCXVersion|null
    {
        return PCXVersion::tryFrom($this->version);
    }
}
