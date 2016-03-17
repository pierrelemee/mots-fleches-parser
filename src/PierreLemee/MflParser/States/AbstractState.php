<?php

namespace PierreLemee\MflParser\States;

use PierreLemee\MflParser\MflParsing;

abstract class AbstractState
{
    protected $buffer;

    public function __construct()
    {
        $this->buffer = "";
    }

    protected function clearBuffer()
    {
        $this->buffer = "";
    }

    public abstract function readEquals(MflParsing $parsing);

    public abstract function readAmpersand(MflParsing $parsing);

    public abstract function readCharacter($character);
}