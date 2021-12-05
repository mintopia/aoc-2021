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
    protected array $data = [];

    public function __construct(string $name = null)
    {
        $className = get_class($this);
        $this->dayNumber = (int) str_replace('Mintopia\Aoc2021\Day', '', $className);
        if ($name === null) {
            $name = "aoc:day{$this->dayNumber}";
        }
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription("Advent of Code Day {$this->dayNumber}");
        $this->addOption('test', 't',  InputOption::VALUE_NONE, 'Use test data');
    }

    protected function getInputFilename(): string
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

        $start = hrtime(true);
        $this->loadData();

        $timing = [
            'Data Loading' => hrtime(true) - $start,
        ];

        $this->io->title('Part 1');

        $start = hrtime(true);
        $result = $this->part1();
        $timing['Part 1'] = hrtime(true) - $start;
        $this->processResult($result);

        $this->io->title('Part 2');
        $start = hrtime(true);
        $result = $this->part2($result);
        $timing['Part 2'] = hrtime(true) - $start;
        $this->processResult($result);

        $this->renderTiming($timing);

        return Command::SUCCESS;
    }

    protected function renderTiming($timing): void
    {
        $table = [];
        foreach ($timing as $description => $nanoSeconds) {
            $ms = round($nanoSeconds / 1000000, 3);
            $table[] = [$description, $ms];
        }
        $this->io->title('Performance');
        $this->io->table(['Section', 'Time (ms)'], $table);
    }

    protected function processResult(Result $result): void
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
            $this->io->success("Answer matches expected: {$assert}");
        } else {
            $this->io->error("Answer does not match expected: {$assert}");
        }
    }

    protected function loadData(): void
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