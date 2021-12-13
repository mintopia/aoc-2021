<?php
namespace Mintopia\Aoc2021\Helpers\Day12;

class Pathfinder
{
    protected $foundPaths = [];

    public function __construct(public Cave $start)
    {
    }

    public function getPaths(bool $visitTwice = false): array
    {
        $this->foundPaths = [];
        $visited = [];
        $this->findPath($this->start, $visited);
        if ($visitTwice) {
            $smallCaves = [];
            foreach ($this->foundPaths as $path) {
                foreach ($path as $cave) {
                    if (in_array($cave, ['start', 'end'])) {
                        continue;
                    }
                    if (strtolower($cave) === $cave) {
                        $smallCaves[$cave] = $cave;
                    }
                }
            }
            foreach ($smallCaves as $cave) {
                $visited = [];
                $this->findPath($this->start, $visited, $cave);
            }
        }
        return $this->foundPaths;
    }

    protected function findPath(Cave $cave, array &$visited, string $doubleVisit = null): void
    {
        $visited[] = $cave->name;
        if ($cave->isEnd) {
            $path = implode("-", $visited);
            $this->foundPaths[$path] = $visited;
            return;
        }

        foreach ($cave->exits as $exit) {
            if ($cave->isStart) {
                $visited = [$cave->name];
            }
            $visitedChild = $visited;
            if ($exit->small) {
                if ($exit->isStart) {
                    continue;
                }

                $values = array_count_values($visitedChild);
                $count = 0;
                if (array_key_exists($exit->name, $values)) {
                    $count = $values[$exit->name];
                }

                if ($count == 1 && $exit->name != $doubleVisit) {
                    continue;
                } elseif ($count > 1) {
                    continue;
                }
                $this->findPath($exit, $visitedChild, $doubleVisit);
            } else {
                $this->findPath($exit, $visitedChild, $doubleVisit);
            }
        }
    }
}