<?php

namespace PierreLemee\MflParser\Model;

class PictureCell extends AbstractCell
{
    public function __construct($x, $y)
    {
        parent::__construct($x, $y);
    }

    public function getType()
    {
        return "pitcure";
    }

    public function getContent()
    {
        return "?";
    }
}