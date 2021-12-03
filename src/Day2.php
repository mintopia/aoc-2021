<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day2 extends Day
{
    protected static $defaultName = 'aoc:day2';
    protected int $dayNumber = 2;

    protected function loadData()
    {
        $data = $this->getArrayFromInputFile();

        foreach ($data as $line) {
            $parts = explode(' ', $line);
            $this->data[] = (object) [
                'direction' => $parts[0],
                'distance' => (int) $parts[1],
            ];
        }
    }

    protected function part1(): Result
    {
        $distance = 0;
        $depth = 0;
        foreach ($this->data as $move) {
            if ($move->direction === 'forward') {
                $distance += $move->distance;
            } elseif ($move->direction === 'down') {
                $depth += $move->distance;
            } elseif ($move->direction === 'up') {
                $depth -= $move->distance;
            }
        }
        $this->output->writeln("Depth: <info>{$depth}</info>");
        $this->output->writeln("Distance: <info>{$distance}</info>");

        $product = $distance * $depth;
        return new Result(Result::PART1, $product);
    }

    protected function part2(Result $part1): Result
    {
        $aim = 0;
        $depth = 0;
        $distance = 0;

        foreach ($this->data as $move) {
            if ($move->direction === 'forward') {
                $distance += $move->distance;
                $depth += $move->distance * $aim;
            } elseif ($move->direction === 'down') {
                $aim += $move->distance;
            } elseif ($move->direction === 'up') {
                $aim -= $move->distance;
            }
        }
        $this->output->writeln("Aim: <info>{$aim}</info>");
        $this->output->writeln("Depth: <info>{$depth}</info>");
        $this->output->writeln("Distance: <info>{$distance}</info>");

        $product = $distance * $depth;

        return new Result(Result::PART2, $product);
    }
}