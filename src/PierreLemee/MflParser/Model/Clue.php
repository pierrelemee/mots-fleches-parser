<?php

namespace PierreLemee\MflParser\Model;

class Clue implements \JsonSerializable
{
    protected $definition;
    protected $force;
    protected $arrow;

    public function __construct($definition, $force, $arrow)
    {
        $this->definition = $definition;
        $this->force      = $force;
        $this->arrow      = $arrow;
    }

    function jsonSerialize()
    {
        return [
            "definition" => $this->definition,
            "force"      => $this->force,
            "arrow"      => $this->arrow,
        ];
    }
}