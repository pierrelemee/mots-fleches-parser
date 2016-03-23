<?php

namespace PierreLemee\MflParser\Model;

class Dashes
{
    const BOTTOM = "bottom";
    const RIGHT  = "right";

    private static $VALUES = [
        self::BOTTOM => 1,
        self::RIGHT  => 2
    ];

    public static function getDashes($value)
    {
        foreach (self::$VALUES as $dashes => $code) {
            if ($code === $value) {
                return $dashes;
            }
        }
        return null;
    }
}