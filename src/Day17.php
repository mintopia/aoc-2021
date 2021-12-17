<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day17 extends Day
{
    protected array $x;
    protected array $y;

    protected function loadData(): void
    {
        $data = file_get_contents($this->getInputFilename());
        preg_match('/^.*x\=(?<x1>-?\d+)\.{2}(?<x2>-?\d+)\, y\=(?<y1>-?\d+)\.{2}(?<y2>-?\d+)$/', $data, $matches);
        $this->x = range($matches['x1'], $matches['x2']);
        $this->y = range($matches['y1'], $matches['y2']);
    }

    protected function part1(): Result
    {
        $heights = $this->getHeights();
        $answer = max($heights);
        return new Result(Result::PART1, $answer, $heights);
    }

    protected function part2(Result $part1): Result
    {
        $answer = count($part1->carry);
        return new Result(Result::PART2, $answer);
    }

    protected function getHeights(): array
    {
        $heights = [];
        for ($vx = 0; $vx <= max($this->x); $vx++) {
            for ($vy = min($this->y); $vy <= abs(min($this->y)); $vy++) {
                $result = $this->fire($vx, $vy);
                if ($result->hit) {
                    $heights[] = $result->height;
                }
            }
        }

        return $heights;
    }

    protected function fire(int $vx, int $vy): object
    {
        $result = (object) [
            'vx' => $vx,
            'vy' => $vy,
            'hit' => false,
            'height' => 0,
            'points' => [],
        ];
        $x = 0;
        $y = 0;
        while (true) {
            [$x, $y, $vx, $vy] = $this->tick($x, $y, $vx, $vy);
            $result->points[] = [
                [$x, $y],
            ];
            $result->height = max($result->height, $y);

            $inX = in_array($x, $this->x);
            $inY = in_array($y, $this->y);

            if ($inX && $inY) {
                $result->hit = true;
                return $result;
            }

            if ($vx == 0 && !$inX) {
                return $result;
            }

            if ($x > max($this->x)) {
                return $result;
            }
            if ($y < min($this->y)) {
                return $result;
            }
        }
    }

    protected function tick(int $x, int $y, int $vx, int $vy): array
    {
        $x += $vx;
        $y += $vy;

        if ($vx > 0) {
            $vx--;
        } elseif ($vx < 0) {
            $vx++;
        }
        $vy--;
        return [$x, $y, $vx, $vy];
    }
}