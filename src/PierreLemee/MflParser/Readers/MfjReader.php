<?php

namespace PierreLemee\MflParser\Readers;

use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Readers\Handlers\AbstractHandler;

class MfjReader extends AbstractReader
{
    private static $HANDLERS;

    const STATE_UNKNOWN = 0;
    const STATE_KEY = 1;
    const STATE_VALUE = 2;

    protected $state;

    public function __construct(GridFile $file)
    {
        parent::__construct($file);
        $this->state = self::STATE_UNKNOWN;
    }

    public function handleDefinitionForce()
    {
        return false;
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
        foreach (scandir(__DIR__ . "/Handlers/Mfj/") as $file) {
            if (preg_match("/Handler\\.php$/", $file)) {
                include_once __DIR__ . "/Handlers/Mfj/" . $file;
                $classname = "\\PierreLemee\\MflParser\\Readers\\Handlers\\Mfj\\" . preg_replace("/Handler\\.php$/", "Handler", $file);
                $class = new $classname;
                if ($class instanceof AbstractHandler) {
                    self::$HANDLERS[] = $class;
                }
            }
        }
    }

    public function readGridFileCharacter($file, $character)
    {
        switch ($character) {
            case "{":
                $this->state = self::STATE_KEY;
                    break;
            case ":":
                $this->key = $this->buffer;
                $this->buffer = "";
                $this->state = self::STATE_VALUE;
                    break;
            case "[":
                if ($this->state >= self::STATE_VALUE) {
                    $this->state++;
                }
                $this->buffer .= $character;
                break;
            case "]":
                if ($this->state >= self::STATE_VALUE) {
                    $this->state--;
                }
                $this->buffer .= $character;
                break;
            case ",":
                if ($this->state === self::STATE_VALUE) {
                    $this->state = self::STATE_KEY;
                    $this->value = $this->buffer;
                    if (null !== $handler = $this->getHandlerForKey($this->key)) {
                        $handler->processEntry($this->key, $this->value, $this->file);
                    } // TODO: throw exception otherwise in strict mode
                    $this->buffer = $this->key = $this->value = "";
                } else {
                    $this->buffer .= $character;
                }
                break;
            case "}":
                $this->state = self::STATE_UNKNOWN;
                break;
            default:
                if (!in_array($character, ["\r", "\t", "\n"]) && $this->state !== self::STATE_UNKNOWN) {
                    $this->buffer .= $character;
                }
        }
    }
}