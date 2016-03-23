<?php

namespace PierreLemee\MflParser\Model;

class Grid implements \JsonSerializable
{
    protected $cells;

    public function __construct()
    {
        $this->cells = [];
    }

    public function addCell(AbstractCell $cell)
    {
        $this->cells[] = $cell;
    }

    function jsonSerialize()
    {
        return $this->cells;
    }
}