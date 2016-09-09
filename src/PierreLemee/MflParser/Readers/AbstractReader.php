<?php

namespace PierreLemee\MflParser\Readers;

use PierreLemee\MflParser\Handlers\AbstractHandler;
use PierreLemee\MflParser\Model\GridFile;

abstract class AbstractReader
{
    protected $key;
    protected $value;
    protected $buffer;
    /**
     * @var GridFile
     */
    protected $file;

    public function __construct(GridFile $file)
    {
        $this->buffer = "";
        $this->file = $file;
    }

    /**
     * @return AbstractHandler[]
     */
    public abstract function getHandlers();

    public function getHandlerForKey($key)
    {
        foreach ($this->getHandlers() as $handler) {
            if ($handler->matches($key)) {
                return $handler;
            }
        }
        return null;
    }

    public abstract function readGridFileCharacter($file, $character);
}