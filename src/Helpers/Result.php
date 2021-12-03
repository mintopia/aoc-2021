<?php
namespace Mintopia\Aoc2021\Helpers;

class Result
{
    const PART1 = 'part1';
    const PART2 = 'part2';

    public $value;
    public $carry;
    public $part;

    public function __construct($part, $value, $carry = null)
    {
        $this->part = $part;
        $this->value = $value;
        $this->carry = $carry;
    }
}