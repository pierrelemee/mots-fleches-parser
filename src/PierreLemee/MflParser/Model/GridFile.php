<?php

namespace PierreLemee\MflParser\Model;


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
    }

    public function getWidth()
    {
    }

    public function getHeight()
    {
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
            if($index == sizeof($this->rows) + 1){
                $this->rows[$index] = array();
                foreach(str_split($row) as $letter){
                    $this->rows[$index][sizeof($this->rows[$index]) + 1] = $letter;
                }
            }
            else{
                throw new \Exception("Can't add row #$index before row {($index - 1)}");
            }
        }
        else{
            throw new \Exception("Row #$index ($row) doesn't fit with Grid width $this->width");
        }
    }

    public function addDefinition($definition, $index)
    {
        if($index == sizeof($this->definitions) + 1){
            $this->definitions[$index] = $definition;
        }
        else{
            throw new \Exception("Can't add definition #$index before definition {($index - 1)}");
        }
    }

    public function __toString()
    {
        $res = "";
        for ($i = 1; $i <= sizeof($this->rows); $i++) {
            for ($j = 1; $j <= sizeof($this->rows[$i]); $j++) {
                $res .=  $this->rows[$i][$j];
            }
            $res .= "\n";
        }
        return $res;
    }
}