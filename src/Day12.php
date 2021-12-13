<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day12\Cave;
use Mintopia\Aoc2021\Helpers\Day12\Pathfinder;
use Mintopia\Aoc2021\Helpers\Result;

class Day12 extends Day
{
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $caves = [];
        foreach ($data as $line) {
            [$source, $destination] = explode("-", $line);
            if (!isset($caves[$source])) {
                $caves[$source] = new Cave($source);
            }
            if (!isset($caves[$destination])) {
                $caves[$destination] = new Cave($destination);
            }
            $caves[$destination]->addExit($caves[$source]);
            $caves[$source]->addExit($caves[$destination]);
        }
        $this->data = $caves;
    }
    protected function part1(): Result
    {
        $pf = new Pathfinder($this->data['start']);
        $paths = $pf->getPaths();
        $number = count($paths);
        $this->displayPaths($paths);

        return new Result(Result::PART1, $number);
    }

    protected function part2(Result $part1): Result
    {
        $pf = new Pathfinder($this->data['start']);
        $paths = $pf->getPaths(true);
        $number = count($paths);
        $this->displayPaths($paths);

        return new Result(Result::PART2, $number);
    }

    protected function displayPaths(array $paths): void
    {
        if ($this->isBenchmark || !$this->isTest) {
            return;
        }

        $lines = [];

        foreach ($paths as $path) {
            $line = "<fg=green>start</> -> <fg=cyan>";
            $path = array_slice($path, 1, -1);
            $line .= implode('</> -> <fg=cyan>', $path);
            if ($path) {
                $line .= '</> -> ';
            }
            $line .= "<fg=red>end</>";
            $lines[] = $line;
        }
        $this->output->writeln($lines);
    }
}