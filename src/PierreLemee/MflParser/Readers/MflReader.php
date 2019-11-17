<?php

namespace PierreLemee\MflParser\Readers;

use Exception;
use PierreLemee\MflParser\Readers\Extract\Grid;

class MflReader extends AbstractReader
{
    /**
     * @param string $filename
     *
     * @return Grid
     *
     * @throws Exception
     */
    protected function doRead(string $filename): Grid
    {
        $row = array_merge(
            ...array_map(
                function ($element) {
                    $parts = explode('=', $element);
                    return count($parts) === 2 ? [$parts[0] => $parts[1]] : [];
                },
                explode('&', file_get_contents($filename) ?? '') ?? []
            )
        );

        if (empty($row)) {
            throw new Exception("Unable to extract data from mfl file {$filename}");
        }

        $extract = new Grid();
        $extract->name = isset($row['titre']) ? preg_replace('/\\n/', '', $row['titre']) : null;
        $extract->legend = isset($row['legende']) ? preg_replace('/\\n/', '', $row['legende']) : null;
        $forces = isset($row['niveau']) ? preg_replace('/\\n/', '', $row['niveau']) : '';
        foreach (str_split($forces) as $force) {
            if (null === $extract->force || $extract->force < $force) {
                $extract->force = $force;
            }

            $extract->forces[] = $force;
        }

        $index = 1;
        while (isset($row["lign$index"])) {
            $extract->cells[] = str_split(preg_replace('/\\n/', '', $row["lign$index"]));
            $index++;
        }
        $extract->width = count(@$extract->cells[0] ?? []);
        $extract->height = count($extract->cells);

        $index = 1;
        while (isset($row["tx$index"])) {
            $extract->definitions[] = preg_replace('/– /', '', preg_replace('/\\n/', ' ', $row["tx$index"]));
            $index++;
        }

        return $extract;
    }

}