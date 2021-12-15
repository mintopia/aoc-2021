<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;
use SplPriorityQueue;

class Day15 extends Day
{
    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->data = array_map('str_split', $data);
    }

    protected function part1(): Result
    {
        $answer = $this->getCost($this->data);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $answer = $this->getCost($this->data, 5);
        return new Result(Result::PART2, $answer);
    }

    protected function getCost(array $grid, int $scale = 1): ?int
    {
        $width = count($grid[0]);
        $height = count($grid);

        $end = [
            ($width * $scale) - 1,
            ($height * $scale) - 1,
        ];

        $queue = new SplPriorityQueue();
        $queue->setExtractFlags(SplPriorityQueue::EXTR_BOTH);
        $queue->insert([0, 0], 0);
        $visited = [
            0 => [
                0 => true,
            ]
        ];

        while (!$queue->isEmpty()) {
            $current = $queue->extract();
            $currentCost = abs($current['priority']);
            [$x, $y] = $current['data'];

            $neighbours = [
                [$x, $y - 1],
                [$x, $y + 1],
                [$x - 1, $y],
                [$x + 1, $y],
            ];
            foreach ($neighbours as [$nx, $ny]) {
                if ($nx > $end[0] || $ny > $end[1]) {
                    continue;
                }
                if (!isset($grid[$ny % $height][$nx % $width])) {
                    continue;
                }
                if (isset($visited[$ny][$nx])) {
                    continue;
                }
                if (!array_key_exists($ny, $visited)) {
                    $visited[$ny] = [];
                }
                $visited[$ny][$nx] = true;

                $newCost = $grid[$ny % $height][$nx % $width];
                $newCost += floor($ny / $height);
                $newCost += floor($nx / $width);
                $newCost = (($newCost - 1) % 9) + 1;

                $newCost += $currentCost;

                if ([$nx, $ny] == $end) {
                    return $newCost;
                } else {
                    $queue->insert([$nx, $ny], -$newCost);
                }
            }
        }

        return null;
    }
}