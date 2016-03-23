<?php

namespace PierreLemee\MflParser\Model;

abstract class AbstractCell implements \JsonSerializable
{
    const CELL_TYPE_CLUE   = "clue";
    const CELL_TYPE_LETTER = "letter";

    public static $TYPES = [
        self::CELL_TYPE_CLUE,
        self::CELL_TYPE_LETTER
    ];

    /**
     * @var $x int
     */
    protected $x;
    /**
     * @var $y int
     */
    protected $y;

    protected function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    public abstract function getType();

    /**
     * @return array
     */
    public abstract function getContent();

    function jsonSerialize()
    {
        return [
            "x"       => $this->x,
            "y"       => $this->y,
            "type"    => $this->getType(),
            "content" => $this->getContent()
        ];
    }


}