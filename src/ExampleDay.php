<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class ExampleDay extends Day
{
    protected function part1(): Result
    {
        return new Result(Result::PART1, 'foo');
    }

    protected function part2(Result $part1): Result
    {
        return new Result(Result::PART2, 'bar');
    }
}