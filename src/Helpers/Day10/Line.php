<?php
namespace Mintopia\Aoc2021\Helpers\Day10;

use Symfony\Component\Console\Output\OutputInterface;

class Line {
    const CHAR_PAIRS = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    const SYNTAX_SCORES = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
    ];

    const AUTOCOMPLETE_SCORES = [
        ')' => 1,
        ']' => 2,
        '}' => 3,
        '>' => 4,
    ];

    protected array $chars;

    public function __construct(public int $lineNumber, string $line)
    {
        $this->chars = str_split($line);
    }

    public function getSyntaxErrorScore(?OutputInterface $output = null): int
    {
        $stack = [];
        foreach ($this->chars as $char) {
            if (array_key_exists($char, self::CHAR_PAIRS)) {
                $stack[] = self::CHAR_PAIRS[$char];
            } else {
                $lastChar = array_pop($stack);
                if ($lastChar != $char) {
                    if ($output) {
                        $output->writeln("{$this->lineNumber}: Expected <fg=cyan>{$lastChar}</> but found <fg=cyan>{$char}</>");
                    }
                    return self::SYNTAX_SCORES[$char];
                }
            }
        }
        return 0;
    }

    public function getAutoCompleteScore(?OutputInterface $output = null): int
    {
        $stack = [];
        foreach ($this->chars as $char) {
            if (array_key_exists($char, self::CHAR_PAIRS)) {
                $stack[] = self::CHAR_PAIRS[$char];
            } else {
                $lastChar = array_pop($stack);
                if ($lastChar != $char) {
                    return 0;
                }
            }
        }
        $score = 0;
        if ($stack) {
            $autoComplete = array_reverse($stack);
            if ($output) {
                $str = implode('', $autoComplete);
                $output->writeln("{$this->lineNumber}: Autocomplete with <fg=cyan>{$str}</>");
            }
            foreach ($autoComplete as $char) {
                $score *= 5;
                $score += self::AUTOCOMPLETE_SCORES[$char];
            }
        }
        return $score;
    }
}