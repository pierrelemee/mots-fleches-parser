<?php

namespace PierreLemee\MflParser;

/**
 * Created by PhpStorm.
 * User: pierrelemee
 * Date: 24/10/2015
 * Time: 11:05
 */
class WordDirection
{
    const DIRECTION_VERTICAL               = 0;
    const DIRECTION_VERTICAL_RIGHT_SHIFTED = 1;
    const DIRECTION_VERTICAL_LEFT_SHIFTED  = 2;
    const DIRECTION_HORIZONTAL             = 3;
    const DIRECTION_HORIZONTAL_SHIFTED     = 4;

    private static $VALUES = [
        self::DIRECTION_VERTICAL => "bfghi",
        self::DIRECTION_VERTICAL_RIGHT_SHIFTED => "cjklmntuvwx",
        self::DIRECTION_VERTICAL_LEFT_SHIFTED => "z",
        self::DIRECTION_HORIZONTAL => "aefghiopqrs",
        self::DIRECTION_HORIZONTAL_SHIFTED => "dopqrstuvwx"
    ];

    /**
     * @param $value string
     * @return string[]
     */
    public static function getDirections($value)
    {
        $directions = [];
        foreach (self::$VALUES as $direction => $values) {
            if (strpos($values, $value)) {
                $directions[] = $direction;
            }
        }
        return $directions;
    }

}