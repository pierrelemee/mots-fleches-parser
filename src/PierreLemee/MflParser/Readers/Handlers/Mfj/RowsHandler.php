<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfj;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class RowsHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/grille/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $index = 0;
        $rows = array_map(function ($row) {
            return substr($row, 1, strlen($row) - 2);
        }, explode(",", substr($value, 1, strlen($value) - 2)));

        foreach ($rows as $row) {
            $file->addRow($row, ++$index);
        }
    }

}