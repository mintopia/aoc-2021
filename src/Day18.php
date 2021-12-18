<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day18\SnailNumber;
use Mintopia\Aoc2021\Helpers\Result;

class Day18 extends Day
{

    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->data = array_map(function(string $input) {
            return new SnailNumber($input);
        }, $data);
        foreach ($this->data as $number) {
            $number->reduce();
        }
    }

    protected function part1(): Result
    {
        $finalNumber = array_reduce($this->data, function(?SnailNumber $previous, SnailNumber $number) {
            if ($previous == null) {
                return $number;
            }
            $newNumber = new SnailNumber("[{$previous},{$number}]");
            $newNumber->reduce();
            return $newNumber;
        }, null);

        $magnitude = $finalNumber->magnitude();
        return new Result(Result::PART1, $magnitude);
    }

    protected function part2(Result $part1): Result
    {
        $magnitude = 0;
        foreach ($this->data as $n1) {
            foreach ($this->data as $n2) {
                if ($n1 !== $n2) {
                    $number = new SnailNumber("[{$n1},{$n2}]");
                    $number->reduce();
                    $magnitude = max($magnitude, $number->magnitude());
                }
            }
        }
        return new Result(Result::PART2, $magnitude);
    }
}