<?php

namespace PierreLemee\MflParser\Model;

class LetterCell extends AbstractCell
{
    protected $letter;
    protected $dashes;

    public function __construct($x, $y, $letter, array $dashes)
    {
        parent::__construct($x, $y);
        $this->letter = $letter;
        $this->dashes = $dashes;
    }

    public function getType()
    {
        return AbstractCell::CELL_TYPE_LETTER;
    }

    public function getContent()
    {
        return [
            "value"  => $this->letter,
            "dashes" => $this->dashes
        ];
    }
}