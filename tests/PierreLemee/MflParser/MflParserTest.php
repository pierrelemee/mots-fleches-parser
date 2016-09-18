<?php

namespace PierreLemee\MflParser;

use PHPUnit\Framework\TestCase;

class MflParserTest extends TestCase
{
    protected $parser;

    protected function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new MflParser();
        }

        return $this->parser;
    }

    /**
     * @dataProvider getGridsOk
     */
    public function testParserOk($filename, $nbclue, $force, $words)
    {
        $grid = $this->getParser()->parse($filename);
        $this->assertEquals($nbclue, sizeof($grid->getClues()));
        $this->assertEquals($force, $grid->getForce());
        foreach ($words as $index => $word) {
            $clue = $grid->getClues()[$index];
            $this->assertNotNull($clue);
            $this->assertEquals($word[0], $clue->getDefinition());
            $this->assertEquals($word[1], $clue->getLabel());
            $this->assertEquals($word[2], $clue->getForce());
            $this->assertEquals($word[3], $clue->getArrow()->getLabel());
        }
    }

    public function getGridsOk()
    {
        return [
            [__DIR__ . '/grids/grid_ok.mfj', 47, 1, [
                    36 => ['IL PEUT MONTER AVEC LA COLÈRE', 'TON', 1, 'bottom']
                ]
            ],
            [__DIR__ . '/grids/grid_ok.mfl', 47, 1, [
                    36 => ['IL PEUT MONTER AVEC LA COLÈRE', 'TON', 2, 'bottom']
                ]
            ]
        ];
    }
}