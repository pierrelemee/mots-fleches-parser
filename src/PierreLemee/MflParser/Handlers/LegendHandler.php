<?php

namespace PierreLemee\MflParser\Handlers;

use PierreLemee\MflParser\Exceptions\MflParserException;
use PierreLemee\MflParser\Model\GridFile;

class LegendHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/legende/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        if(preg_match("/^F[1-9]/", $value)){
            $file->setForce(intval($value{1}));
        }
        else if(preg_match("/Force [1-9]/", $value)){
            $file->setForce(intval($value{strpos($value, "Force ") + strlen("Force ")}));
        }
        else {
            throw new MflParserException(0, 0, "Unable to detect grid level in '$value'");
        }
    }
} 