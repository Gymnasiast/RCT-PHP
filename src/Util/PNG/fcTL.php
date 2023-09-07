<?php
declare(strict_types=1);

namespace RCTPHP\Util\PNG;

use Cyndaron\BinaryHandler\BinaryWriter;
use function fopen;
use function fread;

final class fcTL extends Chunk
{
    public const CHUNK_LENGTH = 26;

    public function __construct(
        public int $sequenceNumber,
        public int $width,
        public int $height,
        public int $xOffset,
        public int $yOffset,
        public int $delayNumerator,
        public int $delayDenominator,
        public int $disposeOp,
        public int $blendOp,
    )
    {
        $dataFp = fopen('php://memory', 'rwb+');
        $dataWriter = new BinaryWriter($dataFp);
        $dataWriter->writeUint32BE($this->sequenceNumber);
        $dataWriter->writeUint32BE($this->width);
        $dataWriter->writeUint32BE($this->height);
        $dataWriter->writeUint32BE($this->xOffset);
        $dataWriter->writeUint32BE($this->yOffset);
        $dataWriter->writeUint16BE($this->delayNumerator);
        $dataWriter->writeUint16BE($this->delayDenominator);
        $dataWriter->writeUint8($this->disposeOp);
        $dataWriter->writeUint8($this->blendOp);

        rewind($dataFp);
        $data = fread($dataFp, self::CHUNK_LENGTH);

        parent::__construct(self::CHUNK_LENGTH, 'fcTL', $data);
    }
}
