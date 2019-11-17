<?php

namespace PierreLemee\MotsFleches\Model;

class Word
{
    const DIRECTION_BOTTOM       = "bottom";
    const DIRECTION_RIGHT_BOTTOM = "right_bottom";
    const DIRECTION_LEFT_BOTTOM  = "left_bottom";
    const DIRECTION_RIGHT        = "right";
    const DIRECTION_BOTTOM_RIGHT = "bottom_right";

    protected $x;
    protected $y;
    protected $direction;
    protected $force;
    /**
     * @var string $definition
     */
    protected $definition;
    protected $content;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     * @return Word
     */
    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     * @return Word
     */
    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     * @return Word
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getForce()
    {
        return $this->force;
    }

    /**
     * @param mixed $force
     * @return Word
     */
    public function setForce($force)
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @return string
     */
    public function getDefinition(): string
    {
        return $this->definition;
    }

    /**
     * @param string $definition
     * @return Word
     */
    public function setDefinition(string $definition): Word
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     * @return Word
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public static function create(string $content): Word
    {
        return (new Word())
            ->setContent($content);
    }
}