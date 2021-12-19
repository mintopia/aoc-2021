<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day19\Scanner;
use Mintopia\Aoc2021\Helpers\Result;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class Day19 extends Day
{


    protected function configure(): void
    {
        parent::configure();
        // Our point matching threshold, default found for our input based on trial/error.
        $this->addOption('points', 'p',  InputOption::VALUE_OPTIONAL, 'Minimum number of matching points', 3);
    }

    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $points = [];
        $i = 1;
        foreach ($data as $datum) {
            if (strpos($datum, 'scanner') !== false) {
                if ($points) {
                    $this->data[] = new Scanner($i, $points);
                    $i++;
                }
                $points = [];
            } else {
                preg_match('/^(?<x>-?\d+),(?<y>-?\d+),(?<z>-?\d+)$/', $datum, $matches);
                $points[] = [$matches['x'], $matches['y'], $matches['z']];
            }
        }
        if ($points) {
            $this->data[] = new Scanner($i, $points);
        }
    }

    protected function part1(): Result
    {
        // Our first is easy and our baseline
        $this->data[0]->rotation = 0;
        $this->data[0]->offset = [0, 0, 0];

        $knownScanners = [
            $this->data[0],
        ];

        $unknownScanners = $this->data;
        unset($unknownScanners[0]);

        $threshold = $this->input->getOption('points');

        while ($unknownScanners) {
            foreach ($unknownScanners as $si => $scanner) {
                foreach ($knownScanners as $ki => $known) {
                    foreach ($scanner->rotations as $i => $rotation) {
                        $offset = $known->getOffset($rotation, $threshold);
                        if ($offset) {
                            $scanner->rotation = $i;
                            $scanner->offset = $offset;
                            $knownScanners[] = $scanner;
                            unset($unknownScanners[$si]);
                            if (!$this->isBenchmark) {
                                $log = "Scanner {$scanner->number} is at <fg=cyan>";
                                $log .= implode(',', $offset);
                                $log .= "</> with rotation {$i}";
                                $this->output->writeln($log);
                            }
                            break 3;
                        }
                    }
                }
            }
        }

        $beacons = [];
        foreach ($this->data as $scanner) {
            foreach ($scanner->getAbsolutePoints() as $point) {
                $beacons[implode(',', $point)] = $point;
            }
        }

        return new Result(Result::PART1, count($beacons));
    }

    protected function part2(Result $part1): Result
    {
        $distance = 0;
        foreach ($this->data as $scanner1) {
            [$x1, $y1, $z1] = $scanner1->offset;
            foreach ($this->data as $scanner2) {
                [$x2, $y2, $z2] = $scanner2->offset;
                $distance = max($distance, abs($x2 - $x1) + abs($y2 - $y1) + abs($z2 - $z1));
            }
        }
        return new Result(Result::PART2, $distance);
    }
}