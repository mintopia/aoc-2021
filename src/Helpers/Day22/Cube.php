<?php
namespace Mintopia\Aoc2021\Helpers\Day22;

class Cube
{
    const COMMAND_ON = 'on';
    const COMMAND_OFF = 'off';

    public string $command;

    public int $zMin;
    public int $zMax;

    public int $yMin;
    public int $yMax;

    public int $xMin;
    public int $xMax;

    public function __construct(string $line)
    {
        preg_match('/^(?<command>off|on) x=(?<x1>-?\d+)..(?<x2>-?\d+),y=(?<y1>-?\d+)..(?<y2>-?\d+),z=(?<z1>-?\d+)..(?<z2>-?\d+)$/', $line, $matches);
        $this->command = $matches['command'];

        $this->zMin = min($matches['z1'], $matches['z2']);
        $this->zMax = max($matches['z1'], $matches['z2']);

        $this->yMin = min($matches['y1'], $matches['y2']);
        $this->yMax = max($matches['y1'], $matches['y2']);

        $this->xMin = min($matches['x1'], $matches['x2']);
        $this->xMax = max($matches['x1'], $matches['x2']);
    }

    public function intersects(Cube $otherCube): bool
    {
        if (max($this->xMin, $otherCube->xMin) > min($this->xMax, $otherCube->xMax)) {
            return false;
        }
        if (max($this->yMin, $otherCube->yMin) > min($this->yMax, $otherCube->yMax)) {
            return false;
        }
        if (max($this->zMin, $otherCube->zMin) > min($this->zMax, $otherCube->zMax)) {
            return false;
        }
        return true;
    }

    public function diff(Cube $otherCube): array
    {
        if (!$this->intersects($otherCube)) {
            return [clone $otherCube];
        }

        // Split other cube into smaller non-intersecting ones
        $newCubes = [];

        foreach (['x', 'y', 'z'] as $dimension) {
            $minProp = "{$dimension}Min";
            $maxProp = "{$dimension}Max";

            if ($otherCube->{$minProp} < $this->{$minProp}) {
                $newCube = clone $otherCube;
                $newCube->{$maxProp} = $this->{$minProp} - 1;
                $newCubes[] = $newCube;
                $otherCube->{$minProp} = $this->{$minProp};
            }

            if ($otherCube->{$maxProp} > $this->{$maxProp}) {
                $newCube = clone $otherCube;
                $newCube->{$minProp} = $this->{$maxProp} + 1;
                $newCubes[] = $newCube;
                $otherCube->{$maxProp} = $this->{$maxProp};
            }
        }

        return $newCubes;
    }

    public function volume(): int
    {
        return
            abs($this->yMax - $this->yMin + 1) *
            abs($this->xMax - $this->xMin + 1) *
            abs($this->zMax - $this->zMin + 1);
    }
}