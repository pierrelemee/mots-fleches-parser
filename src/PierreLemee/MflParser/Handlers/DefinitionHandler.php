<?php

namespace PierreLemee\MflParser\Handlers;

use PierreLemee\MflParser\Model\GridFile;

class DefinitionHandler extends AbstractHandler
{
    const PREFIX = "tx";

    protected function getKeyPattern()
    {
        return sprintf("/^%s[1-9]+[0-9]*$/", self::PREFIX);
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $file->addDefinition(preg_replace("/\n/", "//", $value), $this->getDefinitionIndex($key));
    }

    protected function getDefinitionIndex($key)
    {
        return intval(substr($key, strlen(self::PREFIX)));
    }
}