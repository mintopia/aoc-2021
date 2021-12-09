<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day9 extends Day
{
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->data = [];
        foreach ($data as $datum) {
            $this->data[] = str_split($datum);
        }
    }

    protected function part1(): Result
    {
        $lowPoints = [];
        $risk = 0;
        foreach ($this->data as $y => $row) {
            foreach ($row as $x => $height) {
                if ($this->isLowest($height, $x, $y)) {
                    $lowPoints[] = (object) [
                        'x' => $x,
                        'y' => $y,
                    ];
                    $risk += $height + 1;
                }
            }
        }
        return new Result(Result::PART1, $risk, $lowPoints);
    }

    protected function part2(Result $part1): Result
    {
        $lowPoints = $part1->carry;

        $basins = [];

        foreach ($lowPoints as $lp) {
            [$basin, $checked] = $this->getBasinNeighbours([], $lp->x, $lp->y);
            $basins[] = count($basin);
        }

        sort($basins);
        $largest = array_slice($basins, -3, 3);
        $size = array_product($largest);

        return new Result(Result::PART2, $size);
    }

    protected function isLowest(int $height, int $x, int $y): bool
    {
        $points = [
            [$x, $y - 1],
            [$x - 1, $y],
            [$x + 1, $y],
            [$x, $y + 1],
        ];
        foreach ($points as [$px, $py]) {
            if (!array_key_exists($py, $this->data) || !array_key_exists($px, $this->data[$py])) {
                continue;
            }
            if ($this->data[$py][$px] <= $height) {
                return false;
            }
        }
        return true;
    }

    protected function getBasinNeighbours(array $checked, int $x, int $y): array
    {
        $points = [
            [$x, $y - 1],
            [$x - 1, $y],
            [$x + 1, $y],
            [$x, $y + 1],
        ];

        $heights = [];
        foreach ($points as [$px, $py]) {
            $key = "{$px},{$py}";
            if (in_array($key, $checked)) {
                continue;
            }
            $checked[] = $key;
            if (!array_key_exists($py, $this->data)) {
                continue;
            }
            if (!array_key_exists($px, $this->data[$py])) {
                continue;
            }
            if ($this->data[$py][$px] == 9) {
                continue;
            }
            $heights[] = $this->data[$py][$px];
            [$newHeights, $newChecked] = $this->getBasinNeighbours($checked, $px, $py);
            $heights = array_merge($heights, $newHeights);
            $checked = array_merge($checked, $newChecked);
            $checked = array_unique($checked);
        }
        return [$heights, $checked];
    }
}