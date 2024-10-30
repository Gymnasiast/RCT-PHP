<?php
namespace RCTPHP;

use Cyndaron\BinaryHandler\BinaryReader;
use RCTPHP\RCT2\Object\DATHeader;
use RuntimeException;
use function substr;
use function strtolower;
use function trim;
use function mkdir;
use function is_dir;
use function rename;
use function file_get_contents;
use function preg_replace;
use function str_replace;
use function file_put_contents;
use function sprintf;
use function scandir;
use function chdir;
use function exec;
use function strpos;
use function rtrim;

/**
 * Class DatToJSONConverter
 *
 * This can be used to convert DAT objects to official ones.
 *
 * This was quickly written, and with only my personal use case in mind.
 * If this ends up being used outside my system, a refactor would be in order.
 */
class DatToJSONConverter
{
    public const LANGUAGE_DIR = '/home/michael/Programma\\\'s/Code/CLionProjects/Localisation/data/language';
    public const MAIN_DIR = '/home/michael';
    public const INPUT_DIR = self::MAIN_DIR . '/ToOfficialObjects';
    public const OUTPUT_DIR = self::MAIN_DIR . '/ConvertedOfficialObjects';
    public const OBJEXPORT_PATH = '/home/michael/Programma\\\'s/Code/CLionProjects/objects/tools/objexport/bin/Debug/netcoreapp3.1/objexport';
    public const OPENRCT2_PATH = '/home/michael/Programma\\\'s/OpenRCT2/openrct2';

    public function __construct()
    {
    }

    public function doEverything(): void
    {
        exec('rm -R ' . self::OUTPUT_DIR . '/official 2> /dev/null');
        exec('rm -R ' . self::OUTPUT_DIR . '/other 2> /dev/null');

        $inputFiles = $this->datToJson();

        foreach ($inputFiles as $inputFile)
        {
            chdir(self::MAIN_DIR);

            if (substr($inputFile, 0, 1) === '.')
            {
                continue;
            }

            $reader = BinaryReader::fromFile(self::INPUT_DIR . '/' . $inputFile);
            $DATHeader = DATHeader::fromReader($reader);

            // We use the name embedded in the DAT file, because the filename might differ from it.
            $datName = strtolower(trim($DATHeader->name));
            $typeFolder = DATHeader::TYPE_TO_FOLDER[$DATHeader->getType()->value];
            $oldFile = self::OUTPUT_DIR . "/other/{$typeFolder}/other.{$datName}.json";
            $newDir = self::OUTPUT_DIR . "/official/{$typeFolder}/official.{$datName}";
            if (!mkdir($newDir, 0777, true) && !is_dir($newDir))
            {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $newDir));
            }
            $newFile = "$newDir/object.json";

            rename($oldFile, $newFile);

            $images = $this->extractImages($newDir, $DATHeader);

            $origJson = file_get_contents($newFile);
            if ($origJson === false)
            {
                throw new RuntimeException("Could not read {$newFile}");
            }
            /** @var string $newJson */
            $newJson = preg_replace('/"images": \[.*],/', $images, $origJson);
            $newJson = str_replace('    "objectType"', '    "sourceGame": "official",' . PHP_EOL . '    "objectType"', $newJson);
            file_put_contents($newFile, $newJson);
        }

        exec('rm -R ' . self::OUTPUT_DIR . '/other');
    }

    /**
     * @return string[]
     */
    private function datToJson(): array
    {
        exec(sprintf("%s %s %s --language %s", self::OBJEXPORT_PATH, self::INPUT_DIR, self::OUTPUT_DIR, self::LANGUAGE_DIR));

        return scandir(self::INPUT_DIR) ?: [];
    }

    private function extractImages(string $newDir, DATHeader $DATHeader): string
    {
        $output = [];
        $images = '';
        chdir($newDir);
        @exec(self::OPENRCT2_PATH . " sprite exportalldat {$DATHeader->name} images/ 2> /dev/null", $output);
        // The output may also include warnings. Just save the actual JSON output.
        foreach ($output as $outputLine)
        {
            if (strpos($outputLine, '{') === 0)
            {
                $images .= '        ' . $outputLine . PHP_EOL;
            }
        }
        $images = rtrim($images, ",\n") . PHP_EOL;
        $images = '"images": [' . PHP_EOL . $images . '    ],';
        return $images;
    }
}
