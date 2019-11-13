<?php

namespace PierreLemee\MflParser\Exceptions;

class MflParserException extends \Exception
{
    /**
     * @var $row int
     */
    protected $row;
    /**
     * @var $column int
     */
    protected $column;

    /**
     * @param int $row
     * @param int $column
     * @param string $message
     */
    public function __construct($row, $column, $message)
    {
        $this->row   = $row;
        $this->column = $column;
        parent::__construct(sprintf("Parsing error at row %d, column %d: %s", $this->row, $this->column, $message));
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }
}