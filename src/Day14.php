<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day14 extends Day
{
    protected string $template;
    protected array $rules;

    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->template = (string) array_shift($data);
        $this->rules = [];
        foreach ($data as $rule) {
            [$str, $rep] = explode(' -> ', $rule);
            $this->rules[$str] = $rep;
        }
    }

    protected function part1(): Result
    {
        $answer = $this->scoreChain(10);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = $this->scoreChain(40);
        return new Result(Result::PART2, $answer);
    }

    protected function splitChain(string $chain): array
    {
        $pairs = [];
        for ($i = 0; $i < strlen($chain) - 1; $i++) {
            $pair = substr($chain, $i, 2);
            $pairs = $this->addToKey($pairs, $pair);
        }
        return $pairs;
    }

    protected function addToKey(array $arr, string $key, int $value = 1): array
    {
        if (!array_key_exists($key, $arr)) {
            $arr[$key] = 0;
        }
        $arr[$key] += $value;
        return $arr;
    }

    protected function scoreChain(int $iterations): int
    {
        $charCount = [];
        $pairs = $this->splitChain($this->template);
        foreach (str_split($this->template) as $char) {
            $charCount = $this->addToKey($charCount, $char);
        }
        for ($i = 0; $i < $iterations; $i++) {
            $newPairs = $pairs;
            foreach ($this->rules as $str => $rep) {
                if (!array_key_exists($str, $pairs)) {
                    continue;
                }
                $count = $pairs[$str];

                $charCount = $this->addToKey($charCount, $rep, $count);
                $newPairs = $this->addToKey($newPairs, $str, $count * -1);

                $chars = str_split($str);
                $newPairs = $this->addToKey($newPairs, "{$chars[0]}{$rep}", $count);
                $newPairs = $this->addToKey($newPairs, "{$rep}{$chars[1]}", $count);
            }
            $pairs = $newPairs;
        }

        sort($charCount);
        return end($charCount) - $charCount[0];
    }
}