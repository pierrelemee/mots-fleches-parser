<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfj;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class ForceHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/force/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $file->setForce(intval(substr($value, 1, strlen($value) - 2)));
    }
} 