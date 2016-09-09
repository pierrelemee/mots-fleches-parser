<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfj;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class DefinitionsHandler extends AbstractHandler
{
    protected function getKeyPattern()
    {
        return "/definitions/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $index = 0;
        $row = substr($value, 1, strlen($value) - 2);
        $lbracket = strpos($row, "[");
        $rbracket = strpos($row, "]");
        while (false !== $lbracket && false !== $rbracket) {
            $parts = array_map(function ($d) {
                return substr($d, 1, strlen($d) - 2);
            }, explode(",", substr($row, $lbracket + 1, $rbracket - $lbracket - 1)));
            $definition = "";
            for ($i = 0; $i < sizeof($parts); $i++) {
                $definition .= $parts[$i];
                if ($i < (sizeof($parts) - 1) && ord($parts[$i]{strlen($parts[$i]) - 1}) !== 147) {
                    $definition .= " ";
                }
            }
            $file->addDefinition($definition, ++$index);
            $row = substr($row, $rbracket + 1);
            $lbracket = strpos($row, "[");
            $rbracket = strpos($row, "]");
        }
    }

}