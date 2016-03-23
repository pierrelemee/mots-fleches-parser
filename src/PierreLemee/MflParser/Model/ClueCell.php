<?php

namespace PierreLemee\MflParser\Model;

class ClueCell extends AbstractCell
{
    protected $clues;

    public function __construct($x, $y, array $clues)
    {
        parent::__construct($x, $y);
        $this->clues = $clues;
    }

    public function getType()
    {
        return AbstractCell::CELL_TYPE_CLUE;
    }

    public function getContent()
    {
        return $this->clues;
    }
}