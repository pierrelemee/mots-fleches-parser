<?php

namespace PierreLemee\MflParser\States;

use PierreLemee\MflParser\MflParsing;

class EntryKeyState extends AbstractState
{
    public function readEquals(MflParsing $parsing)
    {
        $parsing->setKey($this->buffer);
        $parsing->setState(MflParsing::STATE_VALUE);
        $this->clearBuffer();
    }

    public function readAmpersand(MflParsing $parsing)
    {
        throw new \Exception("Unexpected & symbol");
    }

    public function readCharacter($character)
    {
        $this->buffer .= $character;
    }
}