<?php
namespace Mintopia\Aoc2021;

class Day2 extends Day
{
    protected static $defaultName = 'aoc:day2';

    protected $title = 'Day 2';

    protected array $data = [];

    protected function loadData()
    {
        $data = file('input/day2.txt', FILE_SKIP_EMPTY_LINES);
        foreach ($data as $line) {
            $parts = explode(' ', $line);
            $this->data[] = (object) [
                'direction' => $parts[0],
                'distance' => (int) $parts[1],
            ];
        }
    }

    protected function part1()
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
        $this->showResult('Result', $product);
    }

    protected function part2($carry)
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
        $this->showResult('Result', $product);
    }
}