<?php

namespace PierreLemee\MflParser;

use Exception;
use PierreLemee\MflParser\Exceptions\MflParserException;
use PierreLemee\MflParser\Model\Grid;
use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\AbstractReader;
use PierreLemee\MflParser\Readers\MfjReader;
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
        }
        throw new MflParserException(0, 0, sprintf("No such file '%s'", $filename));
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

        if ($reader->handleDefinitionForce() && sizeof($file->getDefinitions()) !== sizeof($file->getLevels())) {
            throw new MflParserException($x, $y, sprintf("Number of definitions (%d) and levels (%d) doesn't match", sizeof($file->getDefinitions()), sizeof($file->getLevels())));
        }
        return $file;
    }

    /**
     * @param GridFile $file
     *
     * @return AbstractReader
     * @throws MflParserException
     */
    private static function getReaderForFile(GridFile $file)
    {
        switch(strtolower($file->getExtension())) {
            case "mfl":
            case "txt":
                return new MflReader($file);
            case "mfj":
                return new MfjReader($file);
        }

        throw new MflParserException(0, 0, "Can't find appropriate reader for extension {$file->getExtension()}");
    }
}