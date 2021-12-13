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
        $paths = $this->getPathCount($this->data['start'], [], false);

        return new Result(Result::PART1, $paths);
    }

    protected function part2(Result $part1): Result
    {
        $paths = $this->getPathCount($this->data['start'], [], true);

        return new Result(Result::PART2, $paths);
    }

    protected function getPathCount(Cave $cave, array $visited, bool $doubleVisit): int
    {
        $paths = 0;
        $visited[] = $cave->name;
        foreach ($cave->exits as $exit) {
            if ($exit->isStart) {
                continue;
            }
            if ($exit->isEnd) {
                $paths++;
                continue;
            }
            if ($exit->small && in_array($exit->name, $visited)) {
                if ($doubleVisit) {
                    $paths += $this->getPathCount($exit, $visited, false);
                }
                continue;
            }
            $paths += $this->getPathCount($exit, $visited, $doubleVisit);
        }
        return $paths;
    }
}