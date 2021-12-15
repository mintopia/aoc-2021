<?php
require_once(dirname(__FILE__) . '/vendor/autoload.php');
use Symfony\Component\Console\Application;

$application = new Application();

$commands = [
    new \Mintopia\Aoc2021\RunAll(),
];
for ($i = 1; $i <= 24; $i++) {
    $className = "\\Mintopia\\Aoc2021\\Day{$i}";
    if (class_exists($className)) {
        $commands[] = new $className;
    }
}

$application->addCommands($commands);

$application->setName("Mintopia's Advent of Code 2021");

$application->run();