<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfj;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class PictureAreasHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/photos/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $row = substr($value, 1, strlen($value) - 2);
        $lbracket = strpos($row, "[");
        $rbracket = strpos($row, "]");
        while (false !== $lbracket && false !== $rbracket) {
            $coords = array_map(function ($d) {
                return intval($d);
            }, explode(",", substr($row, $lbracket + 1, $rbracket - $lbracket - 1)));

            if (sizeof($coords) === 4) {
                for ($x = $coords[1]; $x <= $coords[3]; $x++) {
                    for ($y = $coords[0]; $y <= $coords[2]; $y++) {
                        $file->addPicture($x - 1, $y -1);
                    }
                }
            }
            $row = substr($row, $rbracket + 1);
            $lbracket = strpos($row, "[");
            $rbracket = strpos($row, "]");
        }
    }

}
