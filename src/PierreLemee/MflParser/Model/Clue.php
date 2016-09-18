<?php

namespace PierreLemee\MflParser\Model;

class Clue implements \JsonSerializable
{
    protected $definition;
    protected $label;
    protected $force;
    protected $arrow;

    public function __construct($definition, $label, $force, Arrow $arrow)
    {
        $this->definition = $definition;
        $this->label      = $label;
        $this->force      = $force;
        $this->arrow      = $arrow;
    }

    function jsonSerialize()
    {
        return [
            "definition" => $this->definition,
            "label"      => $this->label,
            "force"      => $this->force,
            "arrow"      => $this->arrow->getLabel(),
        ];
    }

    /**
     * @return mixed
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * @return Arrow
     */
    public function getArrow()
    {
        return $this->arrow;
    }
}