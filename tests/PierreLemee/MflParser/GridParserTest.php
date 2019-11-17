<?php

namespace PierreLemee\MotsFleches;

use PHPUnit\Framework\TestCase;
use Exception;
use PierreLemee\MotsFleches\Model\Word;

class GridParserTest extends TestCase
{
    protected $parser;

    protected function getParser()
    {
        if (null === $this->parser) {
            $this->parser = new GridParser();
        }

        return $this->parser;
    }

    /**
     * @dataProvider getGridsOk
     */
    public function testParserOk($filename, $nbclue, $force, $words)
    {
        $grid = $this->getParser()->parseFile($filename);
        $this->assertEquals($nbclue, count($grid->getWords()));
        $this->assertEquals($force, $grid->getForce());
        foreach ($words as $index => $data) {
            $this->assertInstanceOf(Word::class, $word = $grid->getWord($index));
            $this->assertEquals($data[0], $word->getDefinition());
            $this->assertEquals($data[1], $word->getContent());
            $this->assertEquals($data[2], $word->getForce());
            $this->assertEquals($data[3], $word->getDirection());
        }
    }

    public function getGridsOk()
    {
        return [
            [__DIR__ . '/grids/grid_ok.mfj', 47, 1, [
                    36 => ['IL PEUT MONTER, AVEC LA COLÈRE', 'TON', 1, 'bottom']
                ]
            ],
            [__DIR__ . '/grids/grid_ok.mfl', 47, 2, [
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
            $this->getParser()->parseFile($filename);

            $this->fail(sprintf("Expected exception %s to be thrown", Exception::class));
        } catch (Exception $e) {
            $this->assertEquals($message, $e->getMessage());
        }
    }

    public function getGridsKo()
    {
        return [
            "not mfl content" => [$file = (__DIR__ . '/grids/grid_ko.mfl'), "Unable to extract data from mfl file $file" ],
            "not mfj content" => [$file = (__DIR__ . '/grids/grid_ko.mfj'), "Unable to extract data from mfj file $file" ],
        ];
    }
}