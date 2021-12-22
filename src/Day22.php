<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day22\Cube;
use Mintopia\Aoc2021\Helpers\Result;

class Day22 extends Day
{
    protected function getCubes(): array
    {
        return array_map(function(string $line) {
            return new Cube($line);
        }, $this->data);
    }

    protected function part1(): Result
    {
        $cubes = [];
        foreach ($this->getCubes() as $cube) {
            $cube = $this->filterCube($cube, -50, 50);
            if ($cube !== null) {
                $cubes[] = $cube;
            }
        }

        $volume = $this->getVolume($cubes);
        return new Result(Result::PART1, $volume);
    }

    protected function part2(Result $part1): Result
    {
        $volume = $this->getVolume($this->getCubes());
        return new Result(Result::PART2, $volume);
    }

    protected function getVolume(array $allCubes): int
    {
        $cubes = [];

        foreach ($allCubes as $cube) {
            $new = [];
            if ($cube->command == Cube::COMMAND_ON) {
                $new[] = $cube;
            }

            foreach ($cubes as $existingCube) {
                $new = array_merge($new, $cube->diff($existingCube));
            }

            $cubes = $new;
        }

        $volume = 0;
        foreach ($cubes as $cube) {
            $volume += $cube->volume();
        }
        return $volume;
    }

    protected function filterCube(Cube $cube, int $min, int $max): ?Cube
    {
        foreach (['x', 'y', 'z'] as $dimension) {
            $minProp = "{$dimension}Min";
            $maxProp = "{$dimension}Max";

            if ($cube->{$maxProp} < $min) {
                return null;
            }
            if ($cube->{$minProp} > $max) {
                return null;
            }

            $cube->{$minProp} = max($cube->{$minProp}, $min);
            $cube->{$maxProp} = min($cube->{$maxProp}, $max);
        }
        if ($cube->volume() == 0) {
            return null;
        }

        return $cube;
    }
}