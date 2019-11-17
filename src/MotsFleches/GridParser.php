<?php

namespace PierreLemee\MotsFleches;

use Exception;
use PierreLemee\MotsFleches\Model\Grid;
use PierreLemee\MotsFleches\Model\Word;
use PierreLemee\MotsFleches\Readers\AbstractFileReader;
use PierreLemee\MotsFleches\Readers\Extract\Grid as GridExtract;
use PierreLemee\MotsFleches\Readers\MfjFileReader;
use PierreLemee\MotsFleches\Readers\MflFileReader;

class GridParser
{
    private static $DIRECTIONS = [
        'a' => [ Word::DIRECTION_RIGHT ],
        'b' => [ Word::DIRECTION_BOTTOM ],
        'c' => [ Word::DIRECTION_RIGHT_BOTTOM ],
        'd' => [ Word::DIRECTION_BOTTOM_RIGHT ],
        'e' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM ],
        'f' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM ],
        'g' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM ],
        'h' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM ],
        'i' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM ],
        'j' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM ],
        'k' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM ],
        'l' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM ],
        'm' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM ],
        'n' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM ],
        'o' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM_RIGHT ],
        'p' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM_RIGHT ],
        'q' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM_RIGHT ],
        'r' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM_RIGHT ],
        's' => [ Word::DIRECTION_RIGHT, Word::DIRECTION_BOTTOM_RIGHT ],
        't' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM_RIGHT ],
        'u' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM_RIGHT ],
        'v' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM_RIGHT ],
        'w' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM_RIGHT ],
        'x' => [ Word::DIRECTION_RIGHT_BOTTOM, Word::DIRECTION_BOTTOM_RIGHT ],
        'y' => [ Word::DIRECTION_LEFT_BOTTOM],
        'z' => [ Word::DIRECTION_LEFT_BOTTOM, Word::DIRECTION_RIGHT_BOTTOM]
    ];

    /**
     * @param $filename string
     *
     * @return Grid
     *
     * @throws Exception
     */
    public function parseFile($filename)
    {
        $extract = $this
            ->getReaderForFile($filename)
            ->readFile($filename);

        return $this->processExtract($extract);
    }

    /**
     * @param GridExtract $extract
     *
     * @return Grid
     *
     * @throws Exception
     */
    protected function processExtract(GridExtract $extract)
    {
        $grid = new Grid();
        $grid->setWidth($extract->width);
        $grid->setHeight($extract->height);
        $grid->setForce($extract->force);

        if ($grid->getHeight() <= 0) {
            throw new Exception("Invalid height for grid: should be greater than 0");
        }

        if ($grid->getWidth() <= 0) {
            throw new Exception("Invalid width for grid: should be greater than 0");
        }

        if ($grid->getHeight() !== count($extract->cells) || $grid->getWidth() !== count($extract->cells[0])) {
            throw new Exception("Dimensions for grid doesn't match with cells matrix");
        }

        for ($y = 0; $y < $extract->height; $y++) {
            for ($x = 0; $x < $extract->width; $x++) {
                if ($extract->isDefinitionCell($x, $y) && isset(self::$DIRECTIONS[$cell = $extract->getDefinitionCell($x, $y)])) {
                    $words = $this->findWords($extract, $x, $y, self::$DIRECTIONS[$cell]);

                    foreach ($words as $word) {
                        $word
                            ->setDefinition($extract->definitions[$grid->countWords()])
                            ->setForce($extract->forces[$grid->countWords()]);
                        $grid->addWord($word);
                    }
                }
            }
        }

        if (($count = count($grid->getWords())) !== ($expected = count($extract->definitions))) {
            throw new Exception("Number of words extracted ({$count} doesn't match definitions number ({$expected})");
        }

        return $grid;
    }

    /**
     * @param GridExtract $extract
     * @param int $x
     * @param int $y
     * @param string[] $directions
     *
     * @return Word[]
     */
    protected function findWords(GridExtract $extract, int $x, int $y, array $directions): array
    {
        $words = [];
        foreach ($directions as $direction) {
            $content = null;
            switch ($direction) {
                case Word::DIRECTION_RIGHT_BOTTOM:
                    $content = $this->findWordVertical($extract, $x + 1, $y);
                    break;
                case Word::DIRECTION_RIGHT:
                    $content = $this->findWordHorizontal($extract, $x + 1, $y);
                    break;
                case Word::DIRECTION_BOTTOM:
                    $content = $this->findWordVertical($extract, $x, $y + 1);
                    break;
                case Word::DIRECTION_BOTTOM_RIGHT:
                    $content = $this->findWordHorizontal($extract, $x, $y + 1);
                    break;
            }

            if (null !== $content && strlen($content) > 1) {
                $words[] = Word::create($content)
                    ->setX($x)
                    ->setY($y)
                    ->setDirection($direction);
            }
        }

        return $words;
    }

    /**
     * @param GridExtract $extract
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    protected function findWordHorizontal(GridExtract $extract, int $x, int $y): string
    {
        $content = '';
        if (null !== ($cell = $extract->getLetterCell($x, $y)) && null === $extract->getLetterCell($x - 1, $y)) {
            $index = 0;

            do {
                $content .= $cell;
                $cell = $extract->getLetterCell($x + (++$index), $y );
            } while (null !== $cell);
        }

        return $content;
    }

    /**
     * @param GridExtract $extract
     * @param int $x
     * @param int $y
     *
     * @return string
     */
    protected function findWordVertical(GridExtract $extract, int $x, int $y): string
    {
        $content = '';
        if (null !== ($cell = $extract->getLetterCell($x, $y)) && null === $extract->getLetterCell($x, $y - 1)) {
            $index = 0;
            do {
                $content .= $cell;
                $cell = $extract->getLetterCell($x, $y + (++$index));
            } while (null !== $cell);
        }

        return $content;
    }

    /**
     * @param string $filename
     *
     * @return AbstractFileReader
     *
     * @throws Exception
     */
    protected function getReaderForFile(string $filename)
    {
        $extension = strtolower(($index = strrpos(basename($filename), ".")) ? substr(basename($filename), $index + 1) : basename($filename));

        switch(strtolower($extension)) {
            case "mfl":
            case "txt":
                return new MflFileReader();
            case "mfj":
                return new MfjFileReader();
        }

        throw new Exception("No suitable reader can be found for file {$filename}");
    }
}