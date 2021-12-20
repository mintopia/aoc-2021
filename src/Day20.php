<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;

class Day20 extends Day
{
    protected string $key;

    protected function loadData(): void
    {
        $data = $this->getArrayFromInputFile();
        $this->key = str_replace(['.', '#'], [0, 1], array_shift($data));
        $this->data = array_map(function(string $line) {
            $line = str_replace(['.', '#'], [0, 1], $line);
            return str_split($line);
        }, $data);
    }

    protected function part1(): Result
    {
        $image = $this->enhance(2);
        if (!$this->isBenchmark && $this->isTest) {
            $this->renderImage($image);
        }
        $answer = array_sum(array_merge(...$image));
        return new Result(Result::PART1, $answer);
    }

    protected function part2(Result $part1): Result
    {
        $image = $this->enhance(50);
        $answer = array_sum(array_merge(...$image));
        if (!$this->isBenchmark && $this->isTest) {
            $this->renderImage($image);
        }
        return new Result(Result::PART2, $answer);
    }

    protected function enhance(int $count): array
    {
        $image = $this->data;
        $infinite = 0;
        for ($i = 0; $i < $count; $i++) {
            $image = $this->enhanceImage($image, $infinite);

            // Infinite pixel could change
            if ($infinite == 0) {
                $infinite = substr($this->key, 0 ,1);
            } else {
                $infinite = substr($this->key, 511, 1);
            }
        }
        return $image;
    }

    protected function renderImage(array $image): void
    {
        $lines = [''];
        foreach ($image as $y => $row) {
            $line = '';
            foreach ($row as $x => $value) {
                if ($value) {
                    $line .= '<fg=cyan>█</>';
                } else {
                    $line .= ' ';
                }
            }
            $lines[] = $line;
        }
        $this->output->writeln($lines);
    }

    protected function enhanceImage(array $input, int $infinite): array
    {
        $output = [];
        for ($y = -1; $y <= count($input); $y++) {
            $newRow = [];
            for ($x = -1; $x <= count($input[0]); $x++) {
                $xLookup = range($x - 1, $x + 1);
                $yLookup = range($y - 1, $y + 1);
                $bString = '';
                foreach ($yLookup as $yL) {
                    if (array_key_exists($yL, $input)) {
                        foreach ($xLookup as $xL) {
                            if (array_key_exists($xL, $input[$yL])) {
                                $bString .= $input[$yL][$xL];
                            } else {
                                $bString .= $infinite;
                            }
                        }
                    } else {
                        $bString .= str_repeat($infinite, 3);
                    }
                }
                $index = bindec($bString);
                $newRow[] = substr($this->key, $index, 1);

            }
            $output[] = $newRow;
        }
        return $output;
    }
}