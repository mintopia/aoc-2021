<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day14 extends Day
{
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->template = array_shift($data);
        $this->rules = [];
        foreach ($data as $rule) {
            [$str, $rep] = explode(' -> ', $rule);
            $this->rules[$str] = $rep;
        }
    }

    protected function part1(): Result
    {
        $answer = $this->getChainScore(10);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = $this->getChainScore(40);
        return new Result(Result::PART2, $answer);
    }

    protected function getChainScore(int $iterations): int
    {
        $pairs = $this->growChain($iterations);
        $values = $this->countChain($pairs);
        return end($values) - $values[0];
    }

    protected function splitChain(string $chain): array
    {
        $pairs = [];
        for ($i = 0; $i < strlen($chain) - 1; $i++) {
            $pair = substr($chain, $i, 2);
            if (strlen($pair) == 1) {
                throw new \Exception('Uneven template');
            }
            $pairs = $this->addToKey($pairs, $pair);
        }
        return $pairs;
    }

    protected function addToKey(array $arr, string $key, int $value = 1)
    {
        if (!array_key_exists($key, $arr)) {
            $arr[$key] = 0;
        }
        $arr[$key] += $value;
        return $arr;
    }

    protected function growChain(int $iterations): array
    {
        $pairs = $this->splitChain($this->template);
        for ($i = 0; $i < $iterations; $i++) {
            $newPairs = $pairs;
            foreach ($this->rules as $str => $rep) {
                if (!array_key_exists($str, $pairs)) {
                    continue;
                }

                $count = $pairs[$str];
                $newPairs = $this->addToKey($newPairs, $str, $count * -1);
                
                $chars = str_split($str);
                $newPairs = $this->addToKey($newPairs, "{$chars[0]}{$rep}", $count);
                $newPairs = $this->addToKey($newPairs, "{$rep}{$chars[1]}", $count);
            }
            $pairs = $newPairs;
        }

        return $pairs;
    }

    protected function countChain(array $pairs): array
    {
        $counts = [];
        foreach ($pairs as $key => $value) {
            $chars = str_split($key);
            foreach ($chars as $char) {
                $counts = $this->addToKey($counts, $char, $value);
            }
        }
        foreach ($counts as $char => $value) {
            $counts[$char] = ceil($value / 2);
        }
        sort($counts);
        return $counts;
    }
}