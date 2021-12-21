<?php
namespace Mintopia\Aoc2021\Helpers\Day21;

class Dice
{
    public int $lastRoll = 0;
    public int $rolls = 0;

    public function roll(): int
    {
        $this->rolls++;
        $this->lastRoll++;
        if ($this->lastRoll > 100) {
            $this->lastRoll = 1;
        }
        return $this->lastRoll;
    }
}