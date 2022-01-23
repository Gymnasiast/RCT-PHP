<?php
namespace RCTPHP;

use RCTPHP\Object\DatHeader;
use RuntimeException;

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

            $datHeader = new DatHeader(self::INPUT_DIR . '/' . $inputFile);

            // We use the name embedded in the DAT file, because the filename might differ from it.
            $datName = strtolower(trim($datHeader->name));
            $typeFolder = DatHeader::TYPE_TO_FOLDER[$datHeader->getType()];
            $oldFile = self::OUTPUT_DIR . "/other/{$typeFolder}/other.{$datName}.json";
            $newDir = self::OUTPUT_DIR . "/official/{$typeFolder}/official.{$datName}";
            if (!mkdir($newDir, 0777, true) && !is_dir($newDir))
            {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $newDir));
            }
            $newFile = "$newDir/object.json";

            rename($oldFile, $newFile);

            $images = $this->extractImages($newDir, $datHeader);

            $json = file_get_contents($newFile);
            $json = preg_replace('/"images": \[.*],/', $images, $json);
            $json = str_replace('    "objectType"', '    "sourceGame": "official",' . PHP_EOL . '    "objectType"', $json);
            file_put_contents($newFile, $json);
        }

        exec('rm -R ' . self::OUTPUT_DIR . '/other');
    }

    private function datToJson(): array
    {
        exec(sprintf("%s %s %s --language %s", self::OBJEXPORT_PATH, self::INPUT_DIR, self::OUTPUT_DIR, self::LANGUAGE_DIR));

        return scandir(self::INPUT_DIR);
    }

    private function extractImages(string $newDir, DatHeader $datHeader): string
    {
        $output = [];
        $images = '';
        chdir($newDir);
        @exec(self::OPENRCT2_PATH . " sprite exportalldat {$datHeader->name} images/ 2> /dev/null", $output);
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
