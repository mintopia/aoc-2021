<?php
require_once(dirname(__FILE__) . '/vendor/autoload.php');

use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommands([
    new \Mintopia\Aoc2021\RunAll(),
    new \Mintopia\Aoc2021\Day1(),
    new \Mintopia\Aoc2021\Day2(),
    new \Mintopia\Aoc2021\Day3(),
]);

$application->setName("Mintopia's Advent of Code 2021");

$application->run();