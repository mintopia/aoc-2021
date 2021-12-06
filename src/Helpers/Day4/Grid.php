<?php
namespace Mintopia\Aoc2021\Helpers\Day4;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class Grid
{
    public array $rows = [];
    public array $columns = [];

    protected array $marked = [];

    public function __construct(public int $number, array $grid)
    {
        $this->rows = [];
        foreach ($grid as $gridRow) {
            $this->rows[] = array_values(array_filter(explode(' ', $gridRow), function ($input) {
                return $input !== '';
            }));
        }

        $this->columns = array_map(null, ...$this->rows);
    }

    public function mark($number): void
    {
        $this->marked[] = $number;
    }

    public function score(): int
    {
        $ourNumbers = array_merge(...$this->rows);
        $notCalled = array_diff($ourNumbers, $this->marked);
        $sum = array_sum($notCalled);
        return $sum * end($this->marked);
    }

    public function isComplete(): bool
    {
        foreach (array_merge($this->rows, $this->columns) as $row) {
            $intersection = array_intersect($row, $this->marked);
            if (count($intersection) == count($row)) {
                return true;
            }
        }
        return false;
    }

    public function display(OutputInterface $output): void
    {
        $output->writeln([
            "Grid {$this->number}",
            ''
        ]);
        $table = new Table($output);
        $lastIndex = count($this->rows) - 1;
        foreach ($this->rows as $index => $row) {
            $styledRow = [];
            foreach ($row as $number) {
                if (in_array($number, $this->marked)) {
                    $number = "<fg=bright-green>{$number}</>";
                }
                $styledRow[] = $number;
            }
            $table->addRow($styledRow);
            if ($index !== $lastIndex) {
                $table->addRow(new TableSeparator());
            }
        }
        $table->render();
    }
}