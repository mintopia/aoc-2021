<?php
namespace Mintopia\Aoc2021\Helpers;

use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Style\SymfonyStyle;

class Timing
{
    public int $dataLoading;
    public int $part1;
    public int $part2;

    public function getAverages(array $timings): void
    {
        $total = count($timings);
        if ($total == 0) {
            $this->dataLoading = 0;
            $this->part1 = 0;
            $this->part2 = 0;
            return;
        }

        $properties = [
            'dataLoading',
            'part1',
            'part2',
        ];

        foreach ($properties as $propName) {
            $this->{$propName} = round(array_reduce($timings, function(int $carry, Timing $timing) use ($propName) {
                    return $carry + $timing->{$propName};
                }, 0) / $total, 0);
        }
    }

    public function render(SymfonyStyle $io): void
    {
        $total = $this->dataLoading + $this->part1 + $this->part2;
        $table = [
            ['Data Loading', $this->ms($this->dataLoading)],
            ['Part 1', $this->ms($this->part1)],
            ['Part 2', $this->ms($this->part2)],
            new TableSeparator(),
            ['Total', $this->ms($total)],
        ];
        $io->title('Performance');
        $io->table(['Section', 'Time (ms)'], $table);
    }

    protected function ms(int $nanoSeconds): float
    {
        return round($nanoSeconds / 1000000, 3);
    }
}