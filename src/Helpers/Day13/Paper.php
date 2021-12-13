<?php
namespace Mintopia\Aoc2021\Helpers\Day13;

use Symfony\Component\Console\Output\OutputInterface;

class Paper
{
    protected $grid = [];

    public function __construct(array $instructions, array $dots)
    {
        $this->initGrid($instructions);

        foreach ($dots as $dot) {
            [$x, $y] = explode(',', $dot);
            $this->grid[$y][$x] = true;
        }
    }

    protected function initGrid(array $instructions): void
    {
        $index = [
            'x' => [],
            'y' => [],
        ];

        foreach ($instructions as [$xy, $number]) {
            $index[$xy][] = $number;
        }

        $maxX = max($index['x']) * 2;
        $maxY = max($index['y']) * 2;

        $this->grid = array_fill(0, $maxY + 1, array_fill(0, $maxX + 1, false));
    }

    public function getDots(): int
    {
        $dots = 0;
        foreach ($this->grid as $y => $row) {
            $dots += array_sum($row);
        }
        return $dots;
    }

    public function fold(array $instruction): void
    {
        if ($instruction[0] == 'y') {
            $this->grid = $this->foldGrid($this->grid, $instruction[1]);
        } else {
            // Easier if we just transpose it
            $grid = array_map(null, ...$this->grid);
            $grid = $this->foldGrid($grid, $instruction[1]);
            $this->grid = array_map(null, ...$grid);
        }
    }

    protected function foldGrid(array $grid, int $yFold): array
    {
        $upper = array_slice($grid, 0, $yFold);
        $lower = array_reverse(array_slice($grid, $yFold + 1));
        foreach ($lower as $y => $row) {
            foreach ($row as $x => $value) {
                $upper[$y][$x] = $upper[$y][$x] || $value;
            }
        }
        return $upper;
    }

    public function render(OutputInterface $output)
    {
        $lines = [];
        foreach ($this->grid as $y => $row) {
            $line = '';
            foreach ($row as $x => $value) {
                if ($value) {
                    $line .= "<fg=cyan>█</>";
                } else {
                    $line .= ' ';
                }
            }
            $lines[] = $line;
        }
        $lines[] = '';
        $output->writeln($lines);
    }
}