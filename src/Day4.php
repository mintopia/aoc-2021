<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day4\Grid;
use Mintopia\Aoc2021\Helpers\Result;

class Day4 extends Day
{
    protected array $calledNumbers;
    protected array $grids;

    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->calledNumbers = explode(',', $data[0]);
        $count = 1;
        for ($i = 1; $i + 5 <= count($data); $i += 5) {
            $this->grids[] = new Grid($count, array_slice($data, $i, 5));
            $count++;
        }
    }

    protected function playWinningGame(): ?Grid
    {
        foreach ($this->calledNumbers as $number) {
            foreach ($this->grids as $grid) {
                $grid->mark($number);
                if ($grid->isComplete()) {
                    return $grid;
                }
            }
        }
    }

    protected function playLosingGame(): ?Grid
    {
        $grids = $this->grids;
        foreach ($this->calledNumbers as $number) {
            foreach ($grids as $index => $grid) {
                $grid->mark($number);
                if ($grid->isComplete()) {
                    if (count($grids) == 1) {
                        return end($grids);
                    }
                    unset($grids[$index]);
                }
            }
        }
    }

    protected function part1(): Result
    {
        $winningGrid = $this->playWinningGame();
        if (!$winningGrid) {
            throw new \Exception('Unable to find a winning grid');
        }
        if (!$this->isBenchmark) {
            $winningGrid->display($this->io);
        }
        return new Result(Result::PART1, $winningGrid->score());
    }

    protected function part2(Result $part1): Result
    {
        $losingGrid = $this->playLosingGame();
        if (!$losingGrid) {
            throw new \Exception('Unable to find a losing grid');
        }
        if (!$this->isBenchmark) {
            $losingGrid->display($this->output);
        }
        return new Result(Result::PART2, $losingGrid->score());
    }
}