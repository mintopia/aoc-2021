<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Result;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

abstract class Day extends Command
{
    protected InputInterface $input;
    protected OutputInterface $output;
    protected SymfonyStyle $io;

    protected int $dayNumber;
    protected $data = [];

    protected function configure(): void
    {
        $this->setDescription("Advent of Code Day {$this->dayNumber}");
        $this->addOption('test', 't',  InputOption::VALUE_NONE, 'Use test data');
    }

    protected function getInputFilename()
    {
        if ($this->input->getOption('test')) {
            return "testdata/input/day{$this->dayNumber}.txt";
        } else {
            return "input/day{$this->dayNumber}.txt";
        }
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->io = new SymfonyStyle($this->input, $this->output);

        $this->io->block("Advent of Code: Day {$this->dayNumber}", null, 'fg=black;bg=cyan', ' ', true);

        if ($this->input->getOption('test')) {
            $this->io->warning('Using test data');
        }

        $this->loadData();

        $this->io->title('Part 1');
        $result = $this->part1();
        $this->processResult($result);

        $this->io->title('Part 2');
        $result = $this->part2($result);
        $this->processResult($result);

        return Command::SUCCESS;
    }

    protected function processResult(Result $result)
    {
        $this->io->writeln([
            '',
            "Answer: <fg=cyan>{$result->value}</>"
        ]);

        if (!$this->input->getOption('test')) {
            return;
        }

        // Get our known result
        [$part1Answer, $part2Answer] = file("testdata/output/day{$this->dayNumber}.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($result->part === Result::PART1) {
            $assert = $part1Answer;
        } else {
            $assert = $part2Answer;
        }

        // Assertion
        if ($result->value == $assert) {
            $this->io->success("Actual {$result->value} matches Expected {$assert}");
        } else {
            $this->io->error("Actual {$result->value} does not match Expected {$assert}");
        }
    }

    protected function loadData()
    {
        $this->data = $this->getArrayFromInputFile();
    }

    protected function getArrayFromInputFile(): array
    {
        $inputFilename = $this->getInputFilename();
        return file($inputFilename, FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);
    }

    abstract protected function part1(): Result;
    abstract protected function part2(Result $part1): Result;
}