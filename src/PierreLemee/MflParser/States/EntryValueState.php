<?php

namespace PierreLemee\MflParser\States;

use PierreLemee\MflParser\MflParsing;

class EntryValueState extends AbstractState
{
    public function readEquals(MflParsing $parsing)
    {
        throw new \Exception("Unexpected = symbol");
    }

    public function readAmpersand(MflParsing $parsing)
    {
        $parsing->setValue($this->buffer);
        $parsing->setState(MflParsing::STATE_KEY);
        $this->clearBuffer();
    }

    public function readCharacter($character)
    {
        $this->buffer .= $character;
    }
}