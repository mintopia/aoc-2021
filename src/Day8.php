<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day8\Display;
use Mintopia\Aoc2021\Helpers\Result;

class Day8 extends Day
{
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        foreach ($data as $datum) {
            $this->data[] = new Display($datum);
        }
    }

    protected function part1(): Result
    {
        $numbers = [1, 4, 7, 8];
        $count = 0;
        foreach ($this->data as $datum) {
            foreach ($numbers as $number) {
                $count += $datum->getNumberCount($number);
            }
        }
        return new Result(Result::PART1, $count);
    }

    protected function part2(Result $part1): Result
    {
        $total = 0;
        foreach ($this->data as $datum) {
            $total += $datum->decode();
        }
        return new Result(Result::PART2, $total);
    }
}