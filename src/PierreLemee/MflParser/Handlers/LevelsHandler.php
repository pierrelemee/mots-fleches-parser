<?php

namespace PierreLemee\MflParser\Handlers;

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
        $file->setLevels($this->getLevels($value));
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