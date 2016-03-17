<?php

namespace PierreLemee\MflParser\States;

use PierreLemee\MflParser\MflParsing;

class UndefinedState extends AbstractState
{
    public function readEquals(MflParsing $parsing)
    {
        $this->buffer .= "=";
    }

    public function readAmpersand(MflParsing $parsing)
    {
        if (strlen(trim($this->buffer)) > 0) {
            //throw new \Exception("Unexpected token $this->buffer");
        }
        $parsing->setState(MflParsing::STATE_KEY);
        $this->clearBuffer();
    }

    public function readCharacter($character)
    {
        $this->buffer .= $character;
    }
}