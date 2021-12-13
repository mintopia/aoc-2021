<?php
namespace Mintopia\Aoc2021\Helpers\Day12;

class Cave
{
    public bool $small = false;
    public bool $isEnd = false;
    public bool $isStart = false;
    public array $exits = [];

    public function __construct(public string $name)
    {
        if ($name == 'end') {
            $this->isEnd = true;
        } elseif ($name == 'start') {
            $this->isStart = true;
        }
        if (strtolower($name) === $name) {
            $this->small = true;
        }
    }

    public function addExit(Cave $cave): void
    {
        $this->exits[$cave->name] = $cave;
        ksort($this->exits);
    }
}