<?php
namespace Mintopia\Aoc2021\Helpers\Day5;

class Line
{
    protected Point $start;
    protected Point $end;

    public function __construct(protected Map $map, string $input, bool $allowDiagonal = false)
    {
        [$start, $end] = explode(' -> ', $input);
        $this->start = Point::createFromString($start);
        $this->end = Point::createFromString($end);

        $this->createPoints($allowDiagonal);
    }

    protected function createPoints(bool $allowDiagonal): void
    {
        $length = abs($this->end->y - $this->start->y);
        if ($length === 0) {
            $length = abs($this->end->x - $this->start->x);
        }

        $yModifier = 0;
        if ($this->start->y < $this->end->y) {
            $yModifier = 1;
        } elseif ($this->start->y > $this->end->y) {
            $yModifier = -1;
        }

        $xModifier = 0;
        if ($this->start->x < $this->end->x) {
            $xModifier = 1;
        } elseif ($this->start->x > $this->end->x) {
            $xModifier = -1;
        }

        if ($xModifier != 0 && $yModifier != 0 && !$allowDiagonal) {
            return;
        }

        $y = $this->start->y;
        $x = $this->start->x;

        for ($i = 0; $i <= $length; $i++) {
            $this->map->addPoint($x, $y);

            $x += $xModifier;
            $y += $yModifier;
        }
    }
}