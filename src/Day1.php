<?php
namespace Mintopia\Aoc2021;

class Day1 extends Day
{
    protected static $defaultName = 'aoc:day1';

    protected $title = 'Day 1';

    protected array $data;

    protected function loadData()
    {
        $this->data = file('input/day1.txt', FILE_SKIP_EMPTY_LINES);
    }

    protected function part1()
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

        $this->showResult('Result', $larger);
    }

    protected function part2($carry)
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

        $this->showResult('Result', $larger);
    }
}