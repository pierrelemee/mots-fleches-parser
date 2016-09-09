<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfl;

use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;
use PierreLemee\MflParser\Model\GridFile;

class PictureAreasHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/casephotos/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $file->setPictures(preg_replace("/\n/", "", $value));
    }

}
