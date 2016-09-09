<?php

namespace PierreLemee\MflParser\Readers\Handlers\Mfl;

use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;
use PierreLemee\MflParser\Model\GridFile;

class LevelsHandler extends AbstractHandler
{
    /**
     * @return string
     */
    protected function getKeyPattern()
    {
        return "/niveau/";
    }

    public function processEntry($key, $value, GridFile $file)
    {
        $file->addLevels($this->getLevels(preg_replace("/\n/", "", $value)));
    }

    protected function getLevels($value)
    {
        $levels = array();
        foreach (str_split($value) as $level) {
            $levels[] = intval($level);
        }
        return $levels;
    }

} 