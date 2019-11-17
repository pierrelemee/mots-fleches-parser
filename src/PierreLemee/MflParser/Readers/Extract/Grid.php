<?php

namespace PierreLemee\MflParser\Readers\Extract;

class Grid
{
    /**
     * @var $name
     */
    public $name;
    /**
     * @var string $legend
     */
    public $legend;
    public $width;
    public $height;
    /**
     * @var int $force
     */
    public $force;
    /**
     * @var string[] $definitions
     */
    public $definitions;
    /**
     * @var array $cells
     */
    public $cells;
    /**
     * @var int[] $forces
     */
    public $forces;
    /**
     * @var Photo[]
     */
    public $photos;

    public function __construct()
    {
        $this->width = 0;
        $this->height = 0;
        $this->definitions = [];
        $this->forces = [];
        $this->cells = [];
        $this->photos = [];
    }

    public function getCell(int $x, int $y)
    {
        return @$this->cells[$y][$x] ?? null;
    }

    /**
     * @param int $x
     * @param $y
     *
     * @return bool
     */
    public function isLetterCell(int $x, $y)
    {
        return (
            null !== ($cell = $this->getCell($x, $y)) &&
            !$this->isPhotoCell($x, $y) &&
            strtoupper($cell) === $cell
        );
    }

    /**
     * @param int $x
     * @param $y
     *
     * @return string|null
     */
    public function getLetterCell(int $x, $y)
    {
        return (
            null !== ($cell = $this->getCell($x, $y)) &&
            !$this->isPhotoCell($x, $y) &&
            strtoupper($cell) === $cell
        ) ? $cell : null;
    }

    /**
     * @param int $x
     * @param $y
     *
     * @return bool
     */
    public function isDefinitionCell(int $x, $y)
    {
        return (
            null !== ($cell = $this->getCell($x, $y)) &&
            !$this->isPhotoCell($x, $y) &&
            strtolower($cell) === $cell
        );
    }

    /**
     * @param int $x
     * @param $y
     *
     * @return string|null
     */
    public function getDefinitionCell(int $x, $y)
    {
        return (
            null !== ($cell = $this->getCell($x, $y)) &&
            !$this->isPhotoCell($x, $y) &&
            strtolower($cell) === $cell
        ) ? $cell : null;
    }

    /**
     * @param int $x
     * @param int $y
     *
     * @return bool
     */
    public function isPhotoCell(int $x, int $y)
    {
        foreach ($this->photos as $photo) {
            if ($photo->containsCell($x, $y)) {
                return true;
            }
        }

        return false;
    }
}