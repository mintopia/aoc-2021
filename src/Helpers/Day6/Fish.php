<?php
namespace Mintopia\Aoc2021\Helpers\Day6;

class Fish
{
    public function __construct(public int $timer)
    {

    }

    public function tick(): ?Fish
    {
        $this->timer--;
        if ($this->timer < 0) {
            $this->timer = 6;
            return new Fish(8);
        }
        return null;
    }
}