<?php

namespace PierreLemee\MflParser;

use PierreLemee\MflParser\Exceptions\MflParserException;
use PierreLemee\MflParser\Handlers\AbstractHandler;
use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\States\EntryKeyState;
use PierreLemee\MflParser\States\EntryValueState;
use PierreLemee\MflParser\States\UndefinedState;

/**
 * Class MflParsing
 *
 * Should produce levels, rows, definitions, legend, picture areas and dashes
 *
 * @package PierreLemee\MflParser
 */
class MflParsing
{
    protected $row;
    protected $column;
    protected $file;

    /**
     * @var AbstractState
     */    
    protected $state;
    protected $states;
    protected $key;


    protected $levels;
    protected $rows;
    protected $definitions;

    const STATE_UNDEFINED = 0;
    const STATE_KEY       = 1;
    const STATE_VALUE     = 2;

    /**
     * @var AbstractHandler[]
     */
    private static $HANDLERS;

    public function __construct($filename)
    {
        $this->file = new GridFile($filename);
        $this->states = [
            self::STATE_UNDEFINED => new UndefinedState(),
            self::STATE_KEY       => new EntryKeyState(),
            self::STATE_VALUE     => new EntryValueState()
        ];
        $this->initializeHandlers();
        $this->setState(self::STATE_UNDEFINED);
        $this->row = 0;
        $this->column = 0;
        if (is_file(($filename))) {

            $source = fopen($filename, 'r');
            $this->row = 1;
            $this->column = 1;

            try {
                while (($char = fgetc($source)) !== false) {
                    if ($char === "\n") {
                        $this->row++;
                        $this->column = 1;
                    } else {
                        $this->column++;
                    }
                    if ($char === "=") {
                        $this->state->readEquals($this);
                    } else if ($char === "&") {
                        $this->state->readAmpersand($this);
                    } else {
                        $this->state->readCharacter($char);
                    }
                }
            } catch (\Exception $e) {
                echo $e->getTraceAsString();
                throw new MflParserException($this->row, $this->column, $e->getMessage());
            } finally {
                fclose($source);
            }
        }
        else{
            throw new MflParserException($this->row, $this->column, "Grid file not found $filename");
        }
    }

    protected function initializeHandlers()
    {
        if (null === self::$HANDLERS) {
            self::$HANDLERS = [];
            foreach (scandir(__DIR__ . "/Handlers") as $file) {
                if ("AbstractHandler.php" !== $file && preg_match("/Handler\\.php$/", $file)) {
                    include __DIR__ . "/Handlers/" . $file;
                    $classname = "\\PierreLemee\\MflParser\\Handlers\\" . preg_replace("/Handler\\.php$/", "Handler", $file);
                    $class = new $classname;
                    if ($class instanceof AbstractHandler) {
                        self::$HANDLERS[] = $class;
                    }
                }
            }
        }
    }

    /**
     * @return GridFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @param $name string
     *
     * @return AbstractHandler
     * @throws \Exception
     */
    public function getHandler($name)
    {
        // Find appropriate handler
        foreach (self::$HANDLERS as $handler) {
            if ($handler->matches($name)) {
                return $handler;
            }
        }
        //throw new \Exception("No handler found for name $name");
        return null;
    }

    public function setValue($value)
    {
        if (null !== ($handler = $this->getHandler($this->key))) {
            $handler->processEntry($this->key, $value, $this->file);
        }
        $this->key = null;
    }

    public function setState($name)
    {
        $this->state = $this->states[$name];
    }

    /*
    public function setLevels(array $levels)
    {
        $this->levels = $levels;
    }

    public function addRow($row)
    {
        $this->rows[] = $row;
    }

    public function addDefinition($definition)
    {
        $this->definitions[] = $definition;
    }*/
}