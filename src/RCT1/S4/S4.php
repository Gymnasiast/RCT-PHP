<?php
declare(strict_types=1);

namespace RCTPHP\RCT1\S4;

use Cyndaron\BinaryHandler\Reader\Interfaces\IntegerReaderInterface;
use Cyndaron\BinaryHandler\Reader\Interfaces\ReaderInterface;
use RCTPHP\RCT12\Research\List\ResearchLists;
use RCTPHP\Util;
use RCTPHP\Util\Reader\ReadableInterface;
use RCTPHP\Util\Reader\TryFromReaderTrait;
use function strlen;
use function min;
use function chr;
use function ord;
use function abs;

final class S4 implements ReadableInterface
{
    use TryFromReaderTrait;

    public function __construct(
        public readonly ResearchLists $researchLists,
    ) {
    }

    private static function decodeAALLSC4(string $dst): string
    {
        $decodedLength = strlen($dst);

        for ($i = 0x60018; $i <= min($decodedLength - 1, 0x1F8353); $i++)
        {
            $number = ord($dst[$i]);
            $number ^= 0x9C;
            $dst[$i] = chr($number);
        }

        for ($i = 0x60018; $i <= min($decodedLength - 1, 0x1F8350); $i += 4)
        {
            $number = ord($dst[$i + 1]);
            $number = Util::ror8($number, 3);
            $dst[$i + 1] = chr($number);

            $byte0 = ord($dst[$i]);
            $byte1 = ord($dst[$i + 1]);
            $byte2 = ord($dst[$i + 2]);
            $byte3 = ord($dst[$i + 3]);
            $uint32 = $byte0 | ($byte1 << 8) | ($byte2 << 16) | ($byte3 << 24);
            $uint32 = Util::rol32($uint32, 9);

            $byte0 = $uint32 & 0xFF;
            $byte1 = ($uint32 >> 8) & 0xFF;
            $byte2 = ($uint32 >> 16) & 0xFF;
            $byte3 = ($uint32 >> 24) & 0xFF;

            $dst[$i] = chr($byte0);
            $dst[$i + 1] = chr($byte1);
            $dst[$i + 2] = chr($byte2);
            $dst[$i + 3] = chr($byte3);
        }

        return $dst;
    }

    public static function fromReader(ReaderInterface&IntegerReaderInterface $reader): self
    {
        $rleLength = $reader->getSize() - 4;
        $rle = $reader->readBytes($rleLength);
        $checksumInFile = $reader->readUint32();

        $calculatedChecksum = 0;
        for ($i = 0; $i < $rleLength; $i++)
        {
            $calculatedChecksum = ($calculatedChecksum & 0xFFFFFF00) | ((($calculatedChecksum & 0xFF) + ord($rle[$i])) & 0xFF);
            $calculatedChecksum = Util::rol32($calculatedChecksum, 3);
        }

        $difference = $checksumInFile - $calculatedChecksum;
        $gameVersion = abs($difference);
        $isScenario = $difference < 0;

        $isAA = $gameVersion >= 110000 && $gameVersion < 120000;
        $isLL = $gameVersion >= 120000 || $gameVersion === 0;

        $rleString = new \RCTPHP\Sawyer\RLE\RLEString($rle);
        $unRLE = $rleString->decode();

        if (($isAA || $isLL) && $isScenario)
        {
            $unRLE = self::decodeAALLSC4($unRLE);
        }

        $unRLEReader = \Cyndaron\BinaryHandler\BinaryReader::fromString($unRLE);

        if (!$isLL)
        {
            $unRLEReader->moveTo(0x199150);
            $tableSize = 200;
        }
        else
        {
            $unRLEReader->moveTo(0x199C9C);
            $tableSize = 300;// 180;
        }

        $hydrator = new \RCTPHP\RCT1\Research\Hydrator($unRLEReader, $tableSize);
        $entries = $hydrator->readAllEntries();
        $researchLists = ResearchLists::createFromResearchItemList($entries);

        return new self($researchLists);
    }
}
