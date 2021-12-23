<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day23\Map;
use Mintopia\Aoc2021\Helpers\Result;

class Day23 extends Day
{
    protected function part1(): Result
    {
        $map = new Map($this->data);
        $map->render($this->output);
        echo $map->getState();
        die();
        return new Result(Result::PART1, 'foo');
    }

    protected function part2(Result $part1): Result
    {
        return new Result(Result::PART2, 'bar');
    }
}