<?php

namespace PierreLemee\MflParser\Model;

class Arrow
{
    const BOTTOM       = "bottom";
    const RIGHT_BOTTOM = "rightbottom";
    const LEFT_BOTTOM  = "leftbottom";
    const RIGHT        = "right";
    const BOTTOM_RIGHT = "bottomright";

    protected $label;
    protected $mapping;
    protected $dxStart;
    protected $dyStart;
    protected $dx;
    protected $dy;

    private static $VALUES;

    private function __construct($label, $mapping, $dxStart, $dyStart, $dx, $dy)
    {
        $this->label   = $label;
        $this->mapping = $mapping;
        $this->dxStart = $dxStart;
        $this->dyStart = $dyStart;
        $this->dx      = $dx;
        $this->dy      = $dy;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    private static function values()
    {
        if (self::$VALUES === null) {
            self::$VALUES = [
                self::BOTTOM       => new Arrow(self::BOTTOM, "bfghimk", 0, 1, 0, 1),
                self::RIGHT_BOTTOM => new Arrow(self::RIGHT_BOTTOM, "cjklmntuvwx", 1, 0, 0, 1),
                self::LEFT_BOTTOM  => new Arrow(self::LEFT_BOTTOM, "z", -1, 0, 0, 1),
                self::RIGHT        => new Arrow(self::RIGHT, "aefghiopqrs", 1, 0, 1, 0),
                self::BOTTOM_RIGHT => new Arrow(self::BOTTOM_RIGHT, "dopqrstuvwx", 0, 1, 1, 0)
            ];
        }
        return self::$VALUES;
    }

    /**
     * @param $value string
     * @return string[]
     */
    public static function getArrows($value)
    {
        $arrows = [];
        foreach (self::values() as $label => $arrow) {
            if (false !== strpos($arrow->mapping, $value)) {
                $arrows[] = $arrow;
            }
        }
        return $arrows;
    }

    public function firstCell($x, $y)
    {
        return [
            $x + $this->dxStart,
            $y + $this->dyStart,
        ];
    }

    public function nextCell($x, $y)
    {
        return [
            $x + $this->dx,
            $y + $this->dy,
        ];
    }
}