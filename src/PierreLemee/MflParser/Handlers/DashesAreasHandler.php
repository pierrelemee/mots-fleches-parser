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
        $file->addDashes(intval(substr($key, strlen(self::PREFIX))), (int) $value);
    }
} 