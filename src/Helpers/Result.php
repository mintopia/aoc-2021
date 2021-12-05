<?php
namespace Mintopia\Aoc2021\Helpers;

class Result
{
    const PART1 = 'part1';
    const PART2 = 'part2';

    public function __construct(public $part, public $value, public $carry = null)
    {
    }
}