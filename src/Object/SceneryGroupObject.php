<?php
declare(strict_types=1);

namespace RCTPHP\Object;

use RCTPHP\RCT2String;
use RCTPHP\Util;
use RuntimeException;
use function fclose;
use function filesize;
use function fopen;
use function fread;
use function fseek;
use function ord;
use function rewind;
use const SEEK_CUR;
use const STR_PAD_LEFT;

class SceneryGroupObject implements DATObject, StringTableOwner
{
    use StringTableDecoder;

    public DatHeader $header;
    /** @var RCT2String[] */
    public array $stringTable = [];
    /** @var DatHeader[] */
    public array $objects = [];
    public int $numEntries = 0;
    public int $priority;
    // Bitset.
    private int $entertainerCostumes;

    private const ENTERTAINER_CUSTUMES = [
        "panda",
        "tiger",
        "elephant",
        "roman",
        "gorilla",
        "snowman",
        "knight",
        "astronaut",
        "bandit",
        "sheriff",
        "pirate",
    ];

    public function __construct(string $filename)
    {
        $fp = fopen($filename, 'rb');
        if ($fp === false)
        {
            throw new RuntimeException('Could not open file!');
        }

        $this->header = DatHeader::fromStream($fp);
        $restLength = filesize($filename) - 16;
        $rest = fread($fp, $restLength);
        fclose($fp);

        $rledecoded = Util::decodeRLE($rest);

        $fp = fopen('php://memory', 'rwb+');
        fwrite($fp, $rledecoded);

        rewind($fp);
        fseek($fp, 0x10B, SEEK_CUR);
        //        fseek($fp, 6 + (0x80 * 2), SEEK_CUR); // ?
//        fseek($fp, 8, SEEK_CUR);
//        $this->numEntries = ord(fread($fp, 1));
//        fseek($fp, 1, SEEK_CUR);
        $this->priority = ord(fread($fp, 1));

        fseek($fp, 1, SEEK_CUR);
        $this->entertainerCostumes = unpack('V', (fread($fp, 4)))[1] >> 4; // 32-bit little endian

        $this->readStringTable($fp);

        $this->readObjects($fp);


        fclose($fp);
    }

    public function printData(): void
    {
        Util::printLn("DAT name: {$this->header->name}");
        Util::printLn("Priority: {$this->priority}");

        foreach ($this->stringTable as $stringTableItem)
        {
            //if ($stringTableItem->languageCode === 0)
            {
                Util::printLn("In-game name {$stringTableItem->languageCode}: {$stringTableItem->toUtf8()}");
            }
        }

        Util::printLn('');
        Util::printLn("Entertainer custumes:");
        foreach ($this->getEntertainerCostumes() as $costume)
        {
            Util::printLn("  {$costume}");
        }

        Util::printLn('');

        Util::printLn('Objects:');
        Util::printLn('');

        foreach ($this->objects as $objectHeader)
        {
            $flags = str_pad(dechex($objectHeader->flags), 8, "0", STR_PAD_LEFT);
            $paddedName = str_pad($objectHeader->name, 8);
            $string = "{$flags}|{$paddedName}";
            Util::printLn($string);
        }

        Util::printLn('');
    }

    /**
     * @param resource $fp
     * @return void
     */
    private function readObjects(&$fp): void
    {
        while (true)
        {
            $byte = ord(fread($fp, 1));
            if ($byte === 0xFF)
            {
                break;
            }

            fseek($fp, -1, SEEK_CUR);
            $this->objects[] = DatHeader::fromStream($fp);
        }
    }

    public function getEntertainerCostumes(): array
    {
        $ret = [];
        foreach (self::ENTERTAINER_CUSTUMES as $index => $custume)
        {
            if ($this->entertainerCostumes & (1 << $index))
            {
                $ret[] = $custume;
            }
        }

        return $ret;
    }
}
