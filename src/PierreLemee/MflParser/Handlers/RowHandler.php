<?php

namespace PierreLemee\MflParser\Handlers;

use PierreLemee\MflParser\Model\GridFile;

class RowHandler extends AbstractHandler
{
    const PREFIX = "lign";

    protected function getKeyPattern()
    {
        return sprintf("/^%s[1-9]+[0-9]*$/", self::PREFIX);
    }

    /**
     * @param $key
     * @param $value
     * @param GridFile $file
     */
    public function processEntry($key, $value, GridFile $file)
    {
        //if (sizeof($rows) > 0 && strlen($value) !== strlen($rows[sizeof($rows)])) {
        $file->addRow($value, $this->getRowIndex($key));
    }

    protected function getRowIndex($key)
    {
        return intval(substr($key, strlen(self::PREFIX)));
    }

} 