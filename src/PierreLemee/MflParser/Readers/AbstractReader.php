<?php

namespace PierreLemee\MflParser\Readers;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

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
     * @return boolean
     */
    public abstract function handleDefinitionForce();

    /**
     * @return AbstractHandler[]
     */
    public abstract function getHandlers();

    /**
     * @param $key
     * @return AbstractHandler
     */
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