<?php
namespace Mintopia\Aoc2021;

class Day3 extends Day
{
    protected static $defaultName = 'aoc:day3';

    protected $title = 'Day 3';

    protected array $data = [];

    protected function loadData()
    {
        $this->data = file('input/day3.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
    }

    protected function part1()
    {
        $gamma = '';
        $epsilon = '';
        $len = strlen($this->data[0]);
        for ($i = 0; $i < $len; $i++) {
            [$gammaBit, $epsilonBit] = $this->getCommon($this->data, $i);
            $gamma .= $gammaBit;
            $epsilon .= $epsilonBit;
        }

        $e = bindec($epsilon);
        $g = bindec($gamma);

        $this->output->writeln("Epsilon: <info>{$epsilon}</info> = <info>{$e}</info>");
        $this->output->writeln("Gamma: <info>{$gamma}</info> = <info>{$g}</info>");

        $this->showResult('Result', $e * $g);
    }

    protected function getCommon($data, $index)
    {
        $bits = [];
        foreach ($data as $datum) {
            $bits[] = $datum[$index];
        }
        $one = array_sum($bits);
        $half = count($bits) / 2;
        if ($one >= $half) {
            return ['1', '0'];
        } else {
            return ['0', '1'];
        }
    }

    protected function part2($carry)
    {
        $o2 = $this->getCommonValue(0);
        $co2 = $this->getCommonValue(1);

        $o2Dec = bindec($o2);
        $co2Dec = bindec($co2);

        $this->output->writeln("O2 Generator: <info>{$o2}</info> = <info>{$o2Dec}</info>");
        $this->output->writeln("CO2 Scrubber: <info>{$co2}</info> = <info>{$co2Dec}</info>");

        $this->showResult('Result', $o2Dec * $co2Dec);
    }

    protected function getCommonValue($which)
    {
        $filtered = $this->data;
        $len = strlen($this->data[0]);
        for ($i = 0; $i < $len; $i++) {
            $targetValue = $this->getCommon($filtered, $i)[$which];
            $filtered = array_filter($filtered, function($input) use ($i, $targetValue) {
                if ($targetValue == substr($input, $i, 1)) {
                    return true;
                }
                return false;
            });
            if (count($filtered) == 1) {
                return end($filtered);
            }
        }
    }
}