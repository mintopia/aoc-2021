<?php
namespace Mintopia\Aoc2021\Helpers\Day21;

class Player
{
    public int $score = 0;

    public function __construct(public int $space)
    {
    }

    public function turn(Dice $dice): int
    {
        $movement = 0;
        for ($i = 0; $i < 3; $i++) {
            $roll = $dice->roll();
            $movement += $roll;
        }
        $this->space += $movement;
        $this->space %= 10;
        $this->score += $this->space + 1;
        return $this->score;
    }
}