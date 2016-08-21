<?php

namespace PierreLemee\MflParser\Handlers;

use PierreLemee\MflParser\Model\GridFile;

class PictureAreasHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/casephotos/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $file->setPictures($value);
    }

}
