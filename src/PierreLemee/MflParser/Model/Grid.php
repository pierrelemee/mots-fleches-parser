<?php

namespace PierreLemee\MflParser\Model;

use PierreLemee\MflParser\Exceptions\MflParserException;

class Grid implements \JsonSerializable
{
    protected $width;
    protected $height;
    protected $force;
    /**
     * @var AbstractCell[]
     */
    protected $cells;

    public function __construct()
    {
        $this->width = 0;
        $this->height = 0;
        $this->cells = [];
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * @param mixed $force
     */
    public function setForce($force)
    {
        $this->force = $force;
    }

    /**
     * @return AbstractCell[]
     */
    public function getCells()
    {
        return $this->cells;
    }

    public function addCell(AbstractCell $cell)
    {
        $this->cells[] = $cell;
        $this->width = max($this->width, $cell->getX() + 1);
        $this->height = max($this->height, $cell->getY() + 1);
    }

    function jsonSerialize()
    {
        return [
            'force'  => $this->force,
            'width'  => $this->width,
            'height' => $this->height,
            'cells'  => $this->cells
        ];
    }

    /**
     * @param GridFile $gridFile
     * @throws MflParserException
     * @return Grid
     */
    public static function fromGridFile(GridFile $gridFile)
    {
        $grid = new Grid();
        $grid->setForce($gridFile->getForce());
        $index = 1;

        for ($y = 0; $y < $gridFile->getHeight(); $y++) {
            for ($x = 0; $x < $gridFile->getWidth(); $x++) {
                $value = $gridFile->getCell($x, $y);
                if ($gridFile->isPicture($x, $y)) {
                    $grid->addCell(new PictureCell($x, $y));
                }
                else if ($value === strtolower($value)) {
                    $clues = [];
                    $arrows = Arrow::getArrows($value);
                    if (sizeof($arrows) == 0) {
                        throw new MflParserException($x, $y, "Unable to find definitions for $value");
                    }
                    foreach ($arrows as $arrow) {
                        $clues[] = new Clue($gridFile->getDefinitions()[$index], self::getWord($arrow, $gridFile, $x, $y), $gridFile->getLevels()[$index - 1], $arrow);
                        $index++;
                    }
                    $grid->addCell(new ClueCell($x, $y, $clues));
                } else {
                    $grid->addCell(new LetterCell($x, $y, $value, isset($gridFile->getDashes()[$x * sizeof($gridFile->getRows()[0]) + $y + 1]) ? $gridFile->getDashes()[$x * sizeof($gridFile->getRows()[0]) + $y + 1] : []));
                }

            }
        }
        return $grid;
    }

    private static function getWord(Arrow $arrow, GridFile $gridFile, $xStart, $yStart)
    {
        $word = "";
        $cell = $arrow->firstCell($xStart, $yStart);
        list($x, $y) = $cell;
        $value = $gridFile->getCell($x, $y);
        while ($x < $gridFile->getWidth() && $y < $gridFile->getHeight() && $value != strtolower($value)) {
            $word .= $value;
            $cell = $arrow->nextCell($x, $y);
            list($x, $y) = $cell;
            $value = $gridFile->getCell($x, $y);
        }
        return $word;
    }
}