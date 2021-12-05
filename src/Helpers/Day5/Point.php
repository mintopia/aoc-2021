<?php
namespace Mintopia\Aoc2021\Helpers\Day5;

class Point
{
    public int $lines = 0;

    public function __construct(public int $x, public int $y)
    {
    }

    public static function createFromString(string $string): Point
    {
        [$x, $y] = explode(',', $string);
        return new Point($x, $y);
    }
}