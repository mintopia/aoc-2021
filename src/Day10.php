<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day10\Line;
use Mintopia\Aoc2021\Helpers\Result;

class Day10 extends Day
{
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->data = [];
        foreach ($data as $lineNumber => $datum) {
            $this->data[] = new Line($lineNumber, $datum);
        }
    }

    protected function part1(): Result
    {
        $score = array_reduce($this->data, function(int $score, Line $line) {
            return $score + $line->getSyntaxErrorScore($this->getOptionalOutput());
        }, 0);
        return new Result(Result::PART1, $score);
    }

    protected function part2(Result $part1): Result
    {
        $scores = array_map(function(Line $line) {
            return $line->getAutoCompleteScore($this->getOptionalOutput());
        }, $this->data);
        $scores = array_filter($scores, function(int $score) {
            return $score > 0;
        });
        sort($scores);
        $midScore = $scores[floor(count($scores) / 2)];
        return new Result(Result::PART2, $midScore);
    }
}