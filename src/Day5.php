<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day5\Map;
use Mintopia\Aoc2021\Helpers\Result;
use Symfony\Component\Console\Input\InputOption;

class Day5 extends Day
{
    protected bool $hasVisualisation = true;

    protected function renderMap(Map $map): void
    {
        if ($this->isBenchmark) {
            return;
        }
        if ($this->isTest || $this->input->hasOption('visualise')) {
            $map->display($this->output);
        }
    }

    protected function part1(): Result
    {
        $map = new Map($this->data, false);
        $this->renderMap($map);

        return new Result(Result::PART1, $map->intersections);
    }

    protected function part2(Result $part1): Result
    {
        $map = new Map($this->data, true);
        $this->renderMap($map);

        return new Result(Result::PART2, $map->intersections);
    }
}