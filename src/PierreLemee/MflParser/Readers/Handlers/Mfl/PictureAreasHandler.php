<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfl;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class PictureAreasHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/casephotos/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        foreach (str_split(preg_replace("/\n/", "", $value)) as $index => $char) {
            if ($char === "1") {
                $file->addPicture($index % $file->getWidth(), floor($index / $file->getWidth()));
            }
        }
    }

}
