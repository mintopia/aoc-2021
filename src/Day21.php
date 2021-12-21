<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day21\Dice;
use Mintopia\Aoc2021\Helpers\Day21\Player;
use Mintopia\Aoc2021\Helpers\Result;

class Day21 extends Day
{
    protected string $key;

    const DICE_FREQUENCIES = [
        3 => 1,
        4 => 3,
        5 => 6,
        6 => 7,
        7 => 6,
        8 => 3,
        9 => 1,
    ];

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

    public function quantum(int $p1Space, int $p2Space, int $p1Score, int $p2Score, array &$outcomes = []): array
    {
        // Check previous outcomes for this input
        $key = "{$p1Space}-{$p2Space}-{$p1Score}-{$p2Score}";
        if (isset($outcomes[$key])) {
            return $outcomes[$key];
        }

        // Win conditions
        if ($p1Score >= 21) {
            return [1, 0];
        } elseif ($p2Score >= 21) {
            return [0, 1];
        }

        $result = [0, 0];
        foreach (range(3, 9) as $diceRoll) {
            $space = $p1Space + $diceRoll;
            $space %= 10;
            $score = $p1Score + $space + 1;

            // Repeat this but for player 2
            $p2Result = $this->quantum($p2Space, $space, $p2Score, $score, $outcomes);

            // Take our result from Player 2 and add to our current result. Multiply by the number of ways we could have
            // rolled this number.
            $result[0] += $p2Result[1] * self::DICE_FREQUENCIES[$diceRoll];
            $result[1] += $p2Result[0] * self::DICE_FREQUENCIES[$diceRoll];
        }

        // Store outcome for these inputs
        $outcomes[$key] = $result;
        return $result;
    }
}