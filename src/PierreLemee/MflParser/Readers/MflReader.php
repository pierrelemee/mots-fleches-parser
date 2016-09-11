<?php

namespace PierreLemee\MflParser\Readers;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class MflReader extends AbstractReader
{
    private static $HANDLERS;

    public function __construct(GridFile $file)
    {
        parent::__construct($file);
    }

    public function handleDefinitionForce()
    {
        return true;
    }

    public function getHandlers()
    {
        if (null === self::$HANDLERS) {
            $this->initializeHandlers();
        }
        return self::$HANDLERS;
    }

    protected function initializeHandlers()
    {
        // Handlers initialization
        self::$HANDLERS = [];
        foreach (scandir(__DIR__ . "/Handlers/Mfl/") as $file) {
            if (preg_match("/Handler\\.php$/", $file)) {
                include_once __DIR__ . "/Handlers/Mfl/" . $file;
                $classname = "\\PierreLemee\\MflParser\\Readers\\Handlers\\Mfl\\" . preg_replace("/Handler\\.php$/", "Handler", $file);
                $class = new $classname;
                if ($class instanceof AbstractHandler) {
                    self::$HANDLERS[] = $class;
                }
            }
        }
    }

    public function readGridFileCharacter($file, $character)
    {
        if ($character === "=") {
            $this->key = $this->buffer;
            $this->buffer = "";
        } else if ($character === "&") {
            $this->value = $this->buffer;
            if (null !== $handler = $this->getHandlerForKey($this->key)) {
                $handler->processEntry($this->key, $this->value, $this->file);
            } // TODO: throw exception otherwise in strict mode
            $this->buffer = "";
        } else {
            $this->buffer .= $character;
        }
    }
}