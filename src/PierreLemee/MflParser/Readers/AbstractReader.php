<?php

namespace PierreLemee\MflParser\Readers;

use PierreLemee\MflParser\Readers\Extract\Grid;
use Exception;

abstract class AbstractReader
{
    /**
     * @param string $filename
     *
     * @return Grid
     *
     * @throws Exception
     */
    public function readFile(string $filename): Grid
    {
        if (is_file($filename)) {
            return $this->doRead($filename);
        }

        throw new Exception( "No such file '{$filename}'");
    }

    /**
     * @param string $filename
     *
     * @return Grid
     *
     * @throws Exception
     */
    protected abstract function doRead(string $filename): Grid;
}