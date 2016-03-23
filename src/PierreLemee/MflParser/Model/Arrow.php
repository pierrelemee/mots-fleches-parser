<?php

namespace PierreLemee\MflParser\Model;

class Arrow
{
    const BOTTOM       = "bottom";
    const RIGHT_BOTTOM = "rightbottom";
    const LEFT_BOTTOM  = "leftbottom";
    const RIGHT        = "right";
    const BOTTOM_RIGHT = "bottomright";

    private static $VALUES = [
        self::BOTTOM       => "bfghi",
        self::RIGHT_BOTTOM => "cjklmntuvwx",
        self::LEFT_BOTTOM  => "z",
        self::RIGHT        => "aefghiopqrs",
        self::BOTTOM_RIGHT => "dopqrstuvwx"
    ];

    /**
     * @param $value string
     * @return string[]
     */
    public static function getArrows($value)
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