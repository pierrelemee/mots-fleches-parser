<?php

namespace PierreLemee\MflParser;

use PHPUnit\Framework\TestCase;
use PierreLemee\MflParser\Exceptions\MflParserException;

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
                    36 => ['IL PEUT MONTER, AVEC LA COLÈRE', 'TON', 1, 'bottom']
                ]
            ],
            [__DIR__ . '/grids/grid_ok.mfl', 47, 1, [
                    36 => ['IL PEUT MONTER, AVEC LA COLÈRE', 'TON', 2, 'bottom']
                ]
            ]
        ];
    }

    /**
     * @dataProvider getGridsKo
     *
     * @param string $filename
     * @param string $message
     * @param int $row
     * @param int $column
     */
    public function testParserKo($filename, $message, $row = 0, $column = 0)
    {
        try {
            $this->getParser()->parse($filename);

            $this->fail(sprintf("Expected exception %s to be thrown", MflParserException::class));
        } catch (MflParserException $e) {
            $this->assertEquals(sprintf("Parsing error at row %d, column %d: %s", $row, $column, $message), $e->getMessage());
            $this->assertEquals($row, $e->getRow());
            $this->assertEquals($column, $e->getColumn());
        }
    }

    public function getGridsKo()
    {
        return [
            "not mfl content" => [__DIR__ . '/grids/grid_ko.mfl', "Invalid width for grid: should be greater than 0" ],
            "not mfj content" => [__DIR__ . '/grids/grid_ko.mfj', "Invalid width for grid: should be greater than 0" ],
        ];
    }
}