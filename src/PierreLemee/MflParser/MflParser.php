<?php

namespace PierreLemee\MflParser;
use PierreLemee\MflParser\Model\GridFile;

class MflParser
{
    /**
     * @param $filename string
     *
     * @return GridFile
     * @throws \Exception
     */
    public function parse($filename)
    {
        $parsing = new MflParsing($filename);
        return $parsing->getFile();
    }

}