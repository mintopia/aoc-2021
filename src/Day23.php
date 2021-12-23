<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day23\Map;
use Mintopia\Aoc2021\Helpers\Result;

class Day23 extends Day
{
    protected function part1(): Result
    {
        $map = new Map($this->data);
        $cost = $this->nextMove(0, $map);
        return new Result(Result::PART1, $cost);
    }

    protected function part2(Result $part1): Result
    {
        return new Result(Result::PART2, 'bar');
    }

    function nextMove(int $costSoFar, Map $map, array &$states = [], array $movesSoFar = []) {
        $state = $map->getState();
        if (isset($states[$state])) {
            return $state;
        }
        if ($map->isFinished()) {
            echo "Found solution: {$costSoFar}\r\n";
            return $costSoFar;
        }

        $moves = $map->getMoves();
        $lowest = PHP_INT_MAX;
        while (!$moves->isEmpty()) {
            $move = $moves->extract();
            if (in_array([$move->from, $move->to], $movesSoFar)) {
                continue;
            }
            $thisMap = clone $map;
            $thisMap->move($move->from, $move->to);
            $cost = $costSoFar + $move->cost;
            $totalCost = $this->nextMove($cost, $thisMap, $states, array_merge($movesSoFar, [[$move->from, $move->to]]));
            $lowest = min($totalCost, $lowest);
        }
        $states[$state] = $lowest;
        return $lowest;
    }
}