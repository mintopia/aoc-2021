<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day3 extends Day
{
    protected function part1(): Result
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

        if (!$this->isBenchmark) {
            $this->output->writeln("Epsilon: <info>{$epsilon}</info> = <info>{$e}</info>");
            $this->output->writeln("Gamma: <info>{$gamma}</info> = <info>{$g}</info>");
        }

        return new Result(Result::PART1, $e * $g);
    }

    protected function part2(Result $part1): Result
    {
        $o2 = $this->getCommonValue(0);
        $co2 = $this->getCommonValue(1);

        $o2Dec = bindec($o2);
        $co2Dec = bindec($co2);

        if (!$this->isBenchmark) {
            $this->output->writeln("O2 Generator: <info>{$o2}</info> = <info>{$o2Dec}</info>");
            $this->output->writeln("CO2 Scrubber: <info>{$co2}</info> = <info>{$co2Dec}</info>");
        }

        return new Result(Result::PART2, $o2Dec * $co2Dec);
    }

    protected function getCommonValue($which): string
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

    protected function getCommon($data, $index): array
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
}