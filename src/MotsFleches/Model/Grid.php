<?php

namespace PierreLemee\MotsFleches\Model;

class Grid
{
    protected $width;
    protected $height;
    protected $force;
    /**
     * @var Word[]
     */
    protected $words;

    public function __construct()
    {
        $this->width = 0;
        $this->height = 0;
        $this->words = [];
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     * @return Grid
     */
    public function setWidth(int $width): Grid
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     * @return Grid
     */
    public function setHeight(int $height): Grid
    {
        $this->height = $height;
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
     * @return Grid
     */
    public function setForce($force)
    {
        $this->force = $force;
        return $this;
    }

    /**
     * @return Word[]
     */
    public function getWords(): array
    {
        return $this->words;
    }

    /**
     * @return int
     */
    public function countWords(): int
    {
        return count($this->words);
    }

    /**
     * @param Word $word
     *
     * @return Grid
     */
    public function addWord(Word $word): Grid
    {
        $this->words[] = $word;

        return $this;
    }
}