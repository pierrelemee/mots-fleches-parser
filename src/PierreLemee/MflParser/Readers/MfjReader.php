<?php

namespace PierreLemee\MflParser\Readers;

use PierreLemee\MflParser\Readers\Extract\Grid;
use Exception;

class MfjReader extends AbstractReader
{
    protected $script;

    public function __construct()
    {
        $this->script = realpath(__DIR__ . '/scripts/grid_data_extract.js');
    }

    public function doRead(string $filename): Grid
    {
        if (0 !== $this->testCommand('which node') && false !== $this->script) {
            throw new Exception("Missing command 'node', required to parse *.mfj files");
        }

        if (!is_array($row = json_decode(shell_exec("node {$this->script} {$filename}"), true))) {
            throw new Exception("Unable to extract data from mfj file {$filename}");
        }

        $extract = new Grid();
        $extract->name = @$row['title'] ?? null;
        $extract->legend = @$row['legende'] ?? null;
        $extract->force = @$row['force'] ?? 1;
        $extract->cells = array_map('str_split', $row['grille']);
        $extract->height = @$row['nbcaseshauteur'] ?? 0;
        $extract->width = @$row['nbcaseslargeur'] ?? 0;
        $extract->definitions = array_map(
            function ($parts) {
                return preg_replace('/– /', "", implode(' ', $parts));
            },
            $row['definitions']
        );
        $extract->forces = array_fill(0, count($extract->definitions), $extract->force);

        return $extract;
    }

    /**
     * @param $command
     *
     * @return int
     */
    protected function testCommand($command)
    {
        return (int) shell_exec("$command > /dev/null 2>&1; echo $?");
    }
}