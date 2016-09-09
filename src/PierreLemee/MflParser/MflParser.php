<?php

namespace PierreLemee\MflParser;

use PierreLemee\MflParser\Model\Grid;
use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Handlers\AbstractHandler;
use Exception;
use PierreLemee\MflParser\Exceptions\MflParserException;
use PierreLemee\MflParser\Readers\MflReader;

class MflParser
{
    const STATE_UNDEFINED = 0;
    const STATE_KEY       = 1;
    const STATE_VALUE     = 2;

    /**
     * @param $filename string
     *
     * @return Grid
     * @throws MflParserException
     */
    public function parse($filename)
    {
        if (is_file($filename)) {
            return Grid::fromGridFile($this->extractGridFile($filename));
        } else {
            throw new MflParserException(0, 0, sprintf("No such file '%s'", $filename));
        }
    }

    /**
     * @param string $filename
     * @return GridFile
     * @throws MflParserException
     */
    protected function extractGridFile($filename)
    {
        $file = new GridFile($filename);

        $source = $file->getFileHandler();
        $reader = self::getReaderForFile($file);

        if (null === $reader) {

        }
        $x = 1;
        $y = 1;

        try {
            while (($character = fgetc($source)) !== false) {
                if ($character === "\n") {
                    $x++;
                    $y = 1;
                } else {
                    $y++;
                }
                $reader->readGridFileCharacter($file, $character);

            }
        } catch (Exception $e) {
            //echo $e->getTraceAsString();
            throw new MflParserException($x, $y, $e->getMessage());
        } finally {
            fclose($source);
        }

        if (sizeof($file->getDefinitions()) !== sizeof($file->getLevels())) {
            throw new MflParserException($x, $y, sprintf("Number of definitions (%d) and levels (%d) doesn't match", sizeof($file->getDefinitions()), sizeof($file->getLevels())));
        }
        return $file;
    }

    /**
     * @param GridFile $file
     *
     * @return MflReader
     */
    private static function getReaderForFile(GridFile $file)
    {
        switch(strtolower($file->getExtension())) {
            case "mfl":
                return new MflReader($file);
        }

        throw new MflParserException(0, 0, "Can't find appropriate reader for extension {$file->getExtension()}");
    }
}