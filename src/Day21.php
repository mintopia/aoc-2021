<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day21\Dice;
use Mintopia\Aoc2021\Helpers\Day21\Player;
use Mintopia\Aoc2021\Helpers\Result;

class Day21 extends Day
{
    protected string $key;

    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->data = array_map(function(string $line) {
            $words = explode(' ', $line);
            return end($words) - 1;
        }, $data);
    }

    protected function part1(): Result
    {
        $dice = new Dice();
        $player1 = new Player($this->data[0]);
        $player2 = new Player($this->data[1]);
        while (true) {
            if ($player1->turn($dice) >= 1000) {
                break;
            }
            if ($player2->turn($dice) >= 1000) {
                break;
            }
        }

        $answer = $dice->rolls * min($player1->score, $player2->score);
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $result = $this->quantum($this->data[0], $this->data[1], 0, 0);
        $high = max($result);
        return new Result(Result::PART2, $high);
    }

    public function quantum(int $p1Space, int $p2Space, int $p1Score, int $p2Score, array &$outcomes = []) {
        // Win conditions
        if ($p1Score >= 21) {
            return [1, 0];
        } elseif ($p2Score >= 21) {
            return [0, 1];
        }

        // Check previous outcomes for this input
        $key = "{$p1Space}-{$p2Space}-{$p1Score}-{$p2Score}";
        if (isset($outcomes[$key])) {
            return $outcomes[$key];
        }

        // Track our results
        $result = [0, 0];
        foreach (range(1, 3) as $d1) {
            foreach (range(1, 3) as $d2) {
                foreach (range(1, 3) as $d3) {
                    $p1SpaceInner = $p1Space + $d1 + $d2 + $d3;
                    $p1SpaceInner %= 10;
                    $p1ScoreInner = $p1Score + $p1SpaceInner + 1;

                    $p2Result = $this->quantum($p2Space, $p1SpaceInner, $p2Score, $p1ScoreInner, $outcomes);
                    $result[0] += $p2Result[1];
                    $result[1] += $p2Result[0];
                }
            }
        }

        // Store outcome for these inputs
        $key = "{$p1Space}-{$p2Space}-{$p1Score}-{$p2Score}";
        $outcomes[$key] = $result;
        return $result;
    }
}