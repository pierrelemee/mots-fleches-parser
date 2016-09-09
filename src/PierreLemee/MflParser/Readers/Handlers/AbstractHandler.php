<?php

namespace PierreLemee\MflParser\Readers\Handlers;

use PierreLemee\MflParser\Model\GridFile;

abstract class AbstractHandler
{
    protected abstract function getKeyPattern();

    public function matches($key) {
        return preg_match($this->getKeyPattern(), $key);
    }

    public abstract function processEntry($key, $value, GridFile $file);

}