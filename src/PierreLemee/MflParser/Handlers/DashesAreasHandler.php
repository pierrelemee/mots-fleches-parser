<?php

namespace PierreLemee\MflParser\Handlers;

use PierreLemee\MflParser\Model\GridFile;

class DashesAreasHandler extends AbstractHandler
{
    const PREFIX = "pointille";

    protected function getkeyPattern()
    {
        return sprintf("/^%s[1-9]+[0-9]*$/", self::PREFIX);
    }

    public function processEntry($key, $value, GridFile $file)
    {
        /*
        $index = intval(substr($key, strlen(self::PREFIX)));
        $row = (int) ($index / $gridfile->getWidth()) + ($index % $gridfile->getWidth() === 0 ? 0 : 1);
        $column = $index % $gridfile->getWidth() === 0 ? $gridfile->getWidth() : $index % $gridfile->getWidth();
        $gridfile->getCell($row, $column)->setDashes(intval($value));
        */
    }
} 