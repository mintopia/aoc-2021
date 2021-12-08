<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day6 extends Day
{
    protected function loadData(): void
    {
        $input = file_get_contents($this->getInputFilename());
        $this->data = explode(',', $input);
    }

    protected function getFishCount(int $days): int
    {
        $fishes = array_fill(0, 9, 0);
        foreach ($this->data as $age) {
            $fishes[$age]++;
        }

        for ($i = 0; $i < $days; $i++) {
            $fishes = $this->updateFishes($fishes);
        }

        return array_sum($fishes);
    }

    protected function updateFishes(array $fishes): array
    {
        $spawningFish = array_shift($fishes);
        $fishes[6] += $spawningFish;
        $fishes[] = $spawningFish;
        return $fishes;
    }

    protected function part1(): Result
    {
        $count = $this->getFishCount(80);
        return new Result(Result::PART1, $count);
    }

    protected function part2(Result $part1): Result
    {
        $count = $this->getFishCount(256);
        return new Result(Result::PART2, $count);
    }
}