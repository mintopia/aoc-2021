<?php
namespace Mintopia\Aoc2021\Helpers\Day5;

use Symfony\Component\Console\Output\OutputInterface;

class Map
{
    protected array $points = [];

    protected int $yMax = 0;
    protected int $xMax = 0;
    public int $intersections = 0;

    protected array $lines;

    public function __construct(array $data, bool $allowDiagonal)
    {
        foreach ($data as $datum) {
            new Line($this, $datum, $allowDiagonal);
        }
    }

    public function addPoint(int $x, int $y): void
    {
        if ($x > $this->xMax) {
            $this->xMax = $x;
        }
        if ($y > $this->yMax) {
            $this->yMax = $y;
        }

        $key = "{$x},{$y}";
        if (!array_key_exists($key, $this->points)) {
            $this->points[$key] = new Point($x, $y);
        }
        $this->points[$key]->lines++;

        if ($this->points[$key]->lines === 2) {
            $this->intersections++;
        }
    }

    public function display(OutputInterface $output): void
    {
        $lines = [];
        for ($y = 0; $y <= $this->yMax; $y++) {
            $line = '';
            for ($x = 0; $x <= $this->xMax; $x++) {
                $key = "{$x},{$y}";
                $num = '.';
                if (array_key_exists($key, $this->points)) {
                    $num = $this->points[$key]->lines;
                    if ($num == 0) {
                        $num = '.';
                    }
                }
                $line .= $num;
            }
            $lines[] = $line;
        }
        $output->writeln($lines);
    }
}