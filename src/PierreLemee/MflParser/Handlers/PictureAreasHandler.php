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
        /*
        for($i = 0; $i < strlen($value); $i++){
            if(intval($value{$i}) === 1){
                $row = (int) ($i / $gridfile->getWidth()) + ($i % $gridfile->getWidth() === 0 ? 0 : 1);
                $column = $i % $gridfile->getWidth() === 0 ? $gridfile->getWidth() : $i % $gridfile->getWidth();
                $gridfile->getCell($row, $column)->setHasPicture();
            }
        }
        */
    }

}
