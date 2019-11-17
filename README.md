[![Build Status](https://travis-ci.org/pierrelemee/mots-fleches-parser.svg?branch=master)](https://travis-ci.org/pierrelemee/mfl-parser)


# mots-fleches-parser

A simple but handy library to help you parse mots fléchés files, in MFL or MFJ format.

Example:

```php
use PierreLemee\MotsFleches\GridParser;

$parser = new GridParser();
$grid = $parser->parseFile($file = __DIR__ . '/tests/PierreLemee/MflParser/grids/grid_ok.mfj');


echo "Read grid ({$grid->getWidth()}x{$grid->getHeight()}) from file {$file}" . PHP_EOL;

foreach ($grid->getWords() as $word) {
    echo "Definition at ({$word->getX()}:{$word->getY()}) with direction {$word->getDirection()}: {$word->getDefinition()} => {$word->getContent()}" . PHP_EOL;
}
```

**Note:** for MFJ files, the `node` binary is required on the system host