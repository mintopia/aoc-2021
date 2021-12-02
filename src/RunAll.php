<?php
namespace Mintopia\Aoc2021;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunAll extends Command
{
    protected static $defaultName = 'aoc:all';

    protected InputInterface $input;
    protected OutputInterface $output;

    protected function configure(): void
    {
        $this->setDescription("Run all days of Advent of Code");
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 1; $i <= 24; $i++) {
            try {
                $command = $this->getApplication()->find("aoc:day{$i}");
                $command->run($input, $output);
                $output->writeln('');
            } catch (\Exception $e) {
                continue;
            }
        }

        return Command::SUCCESS;
    }
}