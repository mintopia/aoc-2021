<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day1 extends Day
{
    protected function part1(): Result
    {
        $larger = 0;
        $previous = null;
        foreach ($this->data as $line) {
            if ($previous !== null) {
                if ($line > $previous) {
                    $larger++;
                }
            }
            $previous = $line;
        }

        return new Result(Result::PART1, $larger);
    }

    protected function part2(Result $part1): Result
    {
        $larger = 0;
        $previousSum = null;

        foreach ($this->data as $index => $line) {
            $window = array_slice($this->data, $index, 3);
            if (count($window) < 3) {
                continue;
            }
            $sum = array_sum($window);
            if ($previousSum !== null) {
                if ($sum > $previousSum) {
                    $larger++;
                }
            }
            $previousSum = $sum;
        }

        return new Result(Result::PART2, $larger);
    }
}