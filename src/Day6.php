<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day6\Fish;
use Mintopia\Aoc2021\Helpers\Result;

class Day6 extends Day
{
    protected function configure(): void
    {
        parent::configure();
    }

    protected function loadData(): void
    {
        $input = file_get_contents($this->getInputFilename());
        $this->data = explode(',', $input);
    }

    protected function getFishCount(int $days): int
    {
        $fishes = $this->createFishes();
        foreach ($this->data as $age) {
            $fishes[$age]++;
        }

        // Now update each day
        for ($i = 0; $i < $days; $i++) {
            $fishes = $this->updateFishes($fishes);
        }

        return array_sum($fishes);
    }

    protected function createFishes(): array
    {
        $fishes = [];
        for ($i = 0; $i <= 8; $i++) {
            $fishes[$i] = 0;
        }
        return $fishes;
    }

    protected function updateFishes(array $fishes): array
    {
        $newFishes = $this->createFishes();
        foreach ($fishes as $age => $fish) {
            $newAge = $age - 1;
            if ($newAge < 0) {
                $newAge = 6;
                $newFishes[8]++;
            }
            $newFishes[$newAge] += $fish;
        }
        return $newFishes;
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