<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day11\Grid;
use Mintopia\Aoc2021\Helpers\Result;
use Symfony\Component\Console\Input\InputOption;

class Day11 extends Day
{
    protected bool $hasVisualisation = true;

    protected function loadData(): void
    {
        return;
    }

    protected function getGrid(): Grid
    {
        $data = $this->getArrayFromInputFile();
        $sleep = 0;
        if ($this->input->getOption('visualise')) {
            $sleep = 100000;
        }

        return new Grid($data, $this->getOptionalOutput(), $sleep);
    }

    protected function part1(): Result
    {
        $grid = $this->getGrid();
        $flashes = $grid->getFlashes(100);
        return new Result(Result::PART1, $flashes);
    }

    protected function part2(Result $part1): Result
    {
        $grid = $this->getGrid();
        $all = $grid->getSimultaneousFlashes();
        return new Result(Result::PART2, $all);
    }
}