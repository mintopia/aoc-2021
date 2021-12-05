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
        $length = max(
            abs($this->end->y - $this->start->y),
            abs($this->end->x - $this->start->x)
        );

        $yModifier = match(true) {
            $this->start->y < $this->end->y => 1,
            $this->start->y > $this->end->y => -1,
            default => 0
        };

        $xModifier = match(true) {
            $this->start->x < $this->end->x => 1,
            $this->start->x > $this->end->x => -1,
            default => 0
        };

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