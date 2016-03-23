<?php

namespace PierreLemee\MflParser\Model;

use Exception;

class GridFile
{
    protected $filename;
    protected $width;
    protected $height;
    protected $level;
    protected $rows;
    protected $definitions;
    protected $levels;

    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->rows = array();
        $this->definitions = array();
        $this->levels = array();
        $this->dashes = array();
    }

    public function getWidth()
    {
        return isset($this->rows[0]) ? sizeof($this->rows[0]) : null;
    }

    public function getHeight()
    {
        return null !== $this->rows ? sizeof($this->rows) : null;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function setLevels(array $levels)
    {
        $this->levels = $levels;
    }

    public function addRow($row, $index)
    {
        if($this->getWidth() === null || $this->getWidth() === strlen($row)){
            if(($index) > sizeof($this->rows)){
                $this->rows[($index - 1)] = [];
                foreach(str_split($row) as $letter){
                    $this->rows[($index - 1)][sizeof($this->rows[$index - 1])] = $letter;
                }
            }
            else{
                throw new Exception("Can't add row #$index before row {($index)}");
            }
        }
        else{
            throw new Exception("Row #$index ($row) doesn't fit with Grid width $this->width");
        }
    }

    public function addDefinition($definition, $index)
    {
        if($index == sizeof($this->definitions) + 1){
            $this->definitions[$index] = $definition;
        }
        else{
            throw new Exception("Can't add definition #$index before definition {($index - 1)}");
        }
    }

    public function addDashes($index, $value)
    {
        if (!isset($this->dashes[$index])) {
            $this->dashes[$index] = [];
        }
        $this->dashes[$index][] = Dashes::getDashes($value);
    }

    /**
     * @param $row
     * @param $column
     */
    public function getCell($row, $column)
    {
        return $row > 0 && $row <= $this->getHeight() && $column > 0 && $column <= $this->getWidth() ? $this->rows[$row][$column] : null;
    }

    public function getGrid()
    {
        if (sizeof($this->definitions) !== sizeof($this->levels)) {
            throw new Exception(sprintf("Number of definitions (%d) and levels (%d) doesn't match", sizeof($this->definitions), sizeof($this->levels)));
        }

        $grid = new Grid();
        $index = 1;

        for ($i = 0; $i < $this->getHeight(); $i++) {
            for ($j = 0; $j < $this->getWidth(); $j++) {
                if ($this->rows[$i][$j] === strtolower($this->rows[$i][$j])) {
                    $clues = [];
                    foreach(Arrow::getArrows($this->rows[$i][$j]) as $arrow) {
                        $clues[] = new Clue($this->definitions[$index], $this->levels[$index], $arrow);
                        $index++;
                    }
                    $grid->addCell(new ClueCell($i, $j, $clues));
                } else {
                    $grid->addCell(new LetterCell($i, $j, $this->rows[$i][$j], isset($this->dashes[$i * sizeof($this->rows[0]) + $j + 1]) ? $this->dashes[$i * sizeof($this->rows[0]) + $j + 1] : []));
                }


                /*

                if ($this->getCell($i, $j)->isDefinitionToken()) {
                    foreach ($this->getCell($i, $j)->getDefinitionToken()->getDirections() as $direction) {
                        $dashed = "";
                        $row = ($i + $direction->getRow());
                        $column = ($j + $direction->getColumn());
                        while (($cell = $this->getCell($row, $column)) != null && !$cell->isDefinitionToken() && !$cell->hasPicture()) {
                            $dashed .= $cell->getLetter();
                            if(($cell->getDashes() === GridFileCell::DASHES_VERTICAL && $direction->isVertical()) || ($cell->getDashes() === GridFileCell::DASHES_HORIZONTAL && !$direction->isVertical())){
                                $dashed .= GridFileCell::DASH;
                            }
                            $row = $direction->getNextRow($row);
                            $column = $direction->getNextColumn($column);
                        }
                        $grid->addWord($this->getWord($dashed, $this->definitions[sizeof($grid->getWords()) + 1], $this->levels[sizeof($grid->getWords())]));
                    }
                }
                */

            }
        }
        /*
        if (sizeof($this->definitions) != sizeof($grid->getWords())) {
            throw new Exception(sprintf("File '%s': Number of definitions (%d) and words (%d) doesn't match", $this->filename, sizeof($this->definitions), sizeof($grid->getWords())));
        }
        */
        return $grid;
    }

    public function __toString()
    {
        $res = "";
        for ($i = 0; $i < sizeof($this->rows); $i++) {
            for ($j = 0; $j < sizeof($this->rows[$i]); $j++) {
                $res .=  $this->rows[$i][$j];
            }
            $res .= "\n";
        }
        return $res;
    }
}