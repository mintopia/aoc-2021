<?php
namespace Mintopia\Aoc2021;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Day extends Command
{
    protected InputInterface $input;
    protected OutputInterface $output;
    protected SymfonyStyle $io;

    protected function configure(): void
    {

    }

    protected function showHeader()
    {
        $title = "  Advent of Code: {$this->title}  ";
        $padding = str_repeat(' ', strlen($title));

        $this->output->writeln([
            "<bg=cyan;fg=black>{$padding}",
            "{$title}",
            "{$padding}</>",
        ]);
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($this->input, $this->output);

        $this->showHeader();

        $this->loadData();

        $this->io->title('Part 1');
        $carry = $this->part1();

        $this->io->title('Part 2');
        $this->part2($carry);

        return Command::SUCCESS;
    }

    protected function loadData()
    {
    }

    protected function showResult($description, $value)
    {
        $this->io->writeln("{$description}: <fg=cyan>{$value}</>");
    }

    protected function part1()
    {
        return null;
    }

    protected function part2($carry)
    {
        return null;
    }
}