<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day13\Paper;
use Mintopia\Aoc2021\Helpers\Result;

class Day13 extends Day
{
    protected array $dots = [];
    protected array $instructions = [];
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        foreach ($data as $datum) {
            if ($datum === '') {
                continue;
            }
            if (str_contains($datum, 'fold along')) {
                [$text, $number] = explode('=', $datum);

                $this->instructions[] = [
                    substr($text, -1, 1),
                    $number,
                ];
            } else {
                $this->dots[] = $datum;
            }
        }
    }
    protected function part1(): Result
    {
        $paper = new Paper($this->instructions, $this->dots);
        $this->render($paper);
        $paper->fold($this->instructions[0]);
        $this->render($paper);
        $dots = $paper->getDots();

        return new Result(Result::PART1, $dots);
    }

    protected function part2(Result $part1): Result
    {
        $paper = new Paper($this->instructions, $this->dots);
        $this->render($paper, true);

        foreach ($this->instructions as $instruction) {
            $paper->fold($instruction);
            $this->render($paper, true);
        }

        if (!$this->isBenchmark) {
            $paper->render($this->output);
        }

        $dots = $paper->getDots();
        return new Result(Result::PART2, $dots);
    }

    protected function render(Paper $paper): void
    {
        if ($this->isBenchmark || !$this->isTest) {
            return;
        }
        $paper->render($this->output);
    }
}