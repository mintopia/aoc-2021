<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day6\Fish;
use Mintopia\Aoc2021\Helpers\Result;

class Day7 extends Day
{
    protected function configure(): void
    {
        parent::configure();
    }

    protected function loadData(): void
    {
        $input = file_get_contents($this->getInputFilename());
        $this->data = explode(',', $input);
        sort($this->data);
    }

    protected function part1(): Result
    {
        $fuel = $this->getFuel(function(int $movement): int {
            return $movement;
        });

        return new Result(Result::PART1, $fuel);
    }

    protected function getMean(): array
    {
        $total = count($this->data);
        $average = round(array_sum($this->data) / $total, 0);
        return range($average - 1, $average + 1);
    }

    protected function getMedian(): array
    {
        $mid = round(count($this->data) / 2, 0);
        return [$this->data[$mid]];
    }

    protected function getFuel(callable $fn): int
    {
        $targets = array_merge($this->getMean(), $this->getMedian());

        $lowestFuel = PHP_INT_MAX;

        foreach ($targets as $target) {
            $fuel = 0;
            foreach ($this->data as $crab) {
                $movement = abs($crab - $target);
                $cost = $fn($movement);
                $fuel += $cost;
            }
            $lowestFuel = min($lowestFuel, $fuel);
        }

        return $lowestFuel;
    }

    protected function part2(Result $part1): Result
    {
        $fuel = $this->getFuel(function(int $movement): int {
            return ($movement + 1) * ($movement / 2);
        });

        return new Result(Result::PART2, $fuel);
    }
}