<?php
require_once(dirname(__FILE__) . '/vendor/autoload.php');

use Symfony\Component\Console\Application;

$application = new Application();

$application->addCommands([
    new \Mintopia\Aoc2021\Day1(),
    new \Mintopia\Aoc2021\Day2(),
]);

$application->run();