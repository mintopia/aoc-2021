<?php
namespace Mintopia\Aoc2021\Helpers\Day11;

use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Grid
{
    protected array $octo = [];

    protected ?ConsoleSectionOutput $section = null;
    protected ?ConsoleOutputInterface $output = null;

    protected int $flashes = 0;

    public function __construct(array $rows, OutputInterface $output = null, protected int $sleep = 0)
    {
        foreach ($rows as $i => $row) {
            $this->octo[$i] = str_split($row);
        }
        if ($output instanceof ConsoleOutputInterface) {
            $this->output = $output;
            $this->section = $this->output->section();
        }
    }

    public function getFlashes(int $days): int
    {
        $this->render(0, 0);
        for ($i = 0; $i < $days; $i++) {
            $this->flashes += $this->doStep($i);
        }
        return $this->flashes;
    }

    protected function doStep(int $i)
    {
        $this->increaseEnergy();
        $flashes = $this->flash();
        $this->resetEnergy();
        $this->render($i + 1, $flashes);
        if ($this->sleep) {
            usleep($this->sleep);
        }
        return $flashes;
    }

    public function getSimultaneousFlashes(): int
    {
        $i = 1;
        do {
            $flashes = $this->doStep($i);
            $this->flashes += $flashes;
            $i++;
        } while ($flashes != 100);
        return $i - 1;
    }

    protected function render(int $step, int $flashes): void
    {
        if (!$this->section) {
            return;
        }
        $this->section->clear();
        $flashes += $this->flashes;
        $output = [
            "Step {$step}",
            '',
        ];
        foreach ($this->octo as $row) {
            $line = '';
            foreach ($row as $octo) {
                if ($octo == 0) {
                    $line .= "<fg=yellow>0</>";
                } else {
                    $line .= $octo;
                }
            }
            $output[] = $line;
        }
        $output[] = '';
        $output[] = "Flashes: <fg=cyan>{$flashes}</>";
        $this->section->writeln($output);
    }

    protected function flash(): int
    {
        $flashed = [];
        do {
            $hasFlashed = false;
            foreach ($this->octo as $y => $row) {
                foreach ($row as $x => $octo) {
                    if ($octo >= 10) {
                        if (in_array([$x, $y], $flashed)) {
                            continue;
                        }
                        $hasFlashed = true;
                        $flashed[] = [$x, $y];
                        $this->flashOcto($x, $y);
                    }
                }
            }
        } while ($hasFlashed);
        return count($flashed);
    }

    protected function flashOcto(int $octoX, int $octoY)
    {
        $xRange = range($octoX - 1, $octoX + 1);
        $yRange = range($octoY - 1, $octoY + 1);
        foreach ($xRange as $x) {
            foreach ($yRange as $y) {
                if (array_key_exists($y, $this->octo) && array_key_exists($x, $this->octo[$y])) {
                    $this->octo[$y][$x]++;
                }
            }
        }
    }

    protected function resetEnergy()
    {
        foreach ($this->octo as $y => $row) {
            foreach ($row as $x => $octo) {
                if ($octo >= 10) {
                    $this->octo[$y][$x] = 0;
                }
            }
        }
    }

    protected function increaseEnergy()
    {
        foreach ($this->octo as $y => $row) {
            foreach ($row as $x => $octo) {
                $this->octo[$y][$x]++;
            }
        }
    }
}