<?php

namespace PierreLemee\MflParser\Readers\Extract;

class Photo
{
    public $xStart;
    public $yStart;
    public $xStop;
    public $yStop;

    public function containsCell(int $x, int $y)
    {
        return ($x >= $this->xStart && $y >= $this->yStart && $x <= $this->xStop && $y <= $this->yStop);
    }
}