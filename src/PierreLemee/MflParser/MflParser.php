<?php

namespace PierreLemee\MflParser;

use PierreLemee\MflParser\Model\Grid;
use PierreLemee\MflParser\Model\GridFile;
use PierreLemee\MflParser\Handlers\AbstractHandler;
use Exception;
use PierreLemee\MflParser\Exceptions\MflParserException;

class MflParser
{
    const STATE_UNDEFINED = 0;
    const STATE_KEY       = 1;
    const STATE_VALUE     = 2;

    /**
     * @var AbstractHandler[]
     */
    private static $HANDLERS;

    /**
     * @param $filename string
     *
     * @return Grid
     * @throws MflParserException
     */
    public function parse($filename)
    {
        if (is_file($filename)) {
            return Grid::fromGridFile($this->extractGridFile($filename));
        } else {
            throw new MflParserException(0, 0, sprintf("No such file '%s'", $filename));
        }


    }

    /**
     * @param string $filename
     * @return GridFile
     * @throws MflParserException
     */
    protected function extractGridFile($filename)
    {
        $file = new GridFile($filename);

        $source = $file->getFileHandler();
        $x = 1;
        $y = 1;
        $buffer = $key = $value = "";

        try {
            while (($character = fgetc($source)) !== false) {
                if ($character === "\n") {
                    $x++;
                    $y = 1;
                } else {
                    $y++;
                }
                if ($character === "=") {
                    $key = $buffer;
                    $buffer = "";
                } else if ($character === "&") {
                    $value = $buffer;
                    if (null !== $handler = self::getHandlerForKey($key)) {
                        $handler->processEntry($key, $value, $file);
                    } // TODO: throw exception otherwise in strict mode
                    $buffer = "";
                } else {
                    $buffer .= $character;
                }
            }
        } catch (Exception $e) {
            //echo $e->getTraceAsString();
            throw new MflParserException($x, $y, $e->getMessage());
        } finally {
            fclose($source);
        }

        if (sizeof($file->getDefinitions()) !== sizeof($file->getLevels())) {
            throw new MflParserException($x, $y, sprintf("Number of definitions (%d) and levels (%d) doesn't match", sizeof($file->getDefinitions()), sizeof($file->getLevels())));
        }
        return $file;
    }

    private static function initializeHandlers()
    {
        // Handlers initialization
        if (null === self::$HANDLERS) {
            self::$HANDLERS = [];
            foreach (scandir(__DIR__ . "/Handlers") as $file) {
                if ("AbstractHandler.php" !== $file && preg_match("/Handler\\.php$/", $file)) {
                    include_once __DIR__ . "/Handlers/" . $file;
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
     * @param $key
     * @return AbstractHandler
     */
    private static function getHandlerForKey($key)
    {
        self::initializeHandlers();
        foreach (self::$HANDLERS as $handler) {
            if ($handler->matches($key)) {
                return $handler;
            }
        }
        return null;
    }
}