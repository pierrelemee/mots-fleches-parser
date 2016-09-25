<?php

include __DIR__ . '/vendor/autoload.php';

use PierreLemee\MflParser\MflParser;

if ($argc > 0) {
    $parser = new MflParser();
    $grid = $parser->parse($argv[1]);
    echo json_encode($grid);
}