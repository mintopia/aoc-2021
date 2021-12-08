<?php
namespace Mintopia\Aoc2021\Helpers\Day8;

class Display
{
    protected array $patterns = [];
    protected array $output = [];

    protected array $numbers = [];
    protected array $segments = [];

    public function __construct($string)
    {
        [$patterns, $output] = explode(' | ', $string);
        $this->patterns = explode(' ', $patterns);
        $this->output = explode(' ', $output);

        $this->patterns = array_map([$this, 'sortWord'], $this->patterns);
        $this->output = array_map([$this, 'sortWord'], $this->output);

        $this->decodePatterns();
    }

    protected function sortWord(string $input): string
    {
        $chars = str_split($input);
        sort($chars);
        return implode('', $chars);
    }

    protected function countCharsInString(string $string, string $chars): int
    {
        $chars = str_split($chars);
        $string = str_split($string);
        return count(array_intersect($string, $chars));
    }

    public function decodePatterns(): void
    {
        $this->decode1478();
        $this->decode369();
        $this->decode250();
    }

    protected function decode369()
    {
        foreach ($this->patterns as $pattern) {
            if (array_key_exists($pattern, $this->numbers)) {
                continue;
            }

            $length = strlen($pattern);
            if ($length == 5 && $this->countCharsInString($pattern, $this->segments[1]) == 2) {
                $this->segments[3] = $pattern;
                $this->numbers[$pattern] = 3;
            } elseif ($length == 6) {
                if ($this->countCharsInString($pattern, $this->segments[4]) == 4) {
                    $this->segments[9] = $pattern;
                    $this->numbers[$pattern] = 9;
                } elseif ($this->countCharsInString($pattern, $this->segments[1]) == 1) {
                    $this->segments[6] = $pattern;
                    $this->numbers[$pattern] = 6;
                }
            }
        }
    }

    protected function decode250()
    {
        foreach ($this->patterns as $pattern) {
            if (array_key_exists($pattern, $this->numbers)) {
                continue;
            }

            $length = strlen($pattern);
            if ($length == 6) {
                $this->segments[0] = $pattern;
                $this->numbers[$pattern] = 0;
            } elseif ($length == 5) {
                $charCount = $this->countCharsInString($pattern, $this->segments[6]);
                if ($charCount == 5) {
                    $this->segments[5] = $pattern;
                    $this->numbers[$pattern] = 5;
                } elseif ($charCount == 4) {
                    $this->segments[2] = $pattern;
                    $this->numbers[$pattern] = 2;
                }
            }
        }
    }

    protected function decode1478()
    {
        foreach ($this->patterns as $pattern) {
            $length = strlen($pattern);
            switch ($length) {
                case 2:
                    $this->segments[1] = $pattern;
                    $this->numbers[$pattern] = 1;
                    break;
                case 3:
                    $this->segments[7] = $pattern;
                    $this->numbers[$pattern] = 7;
                    break;
                case 4:
                    $this->segments[4] = $pattern;
                    $this->numbers[$pattern] = 4;
                    break;
                case 7:
                    $this->segments[8] = $pattern;
                    $this->numbers[$pattern] = 8;
                    break;

                default:
                    break;
            }
        }
    }

    public function decode(): int
    {
        $value = '';
        foreach ($this->output as $output) {
            if (!array_key_exists($output, $this->numbers)) {
                throw new \Exception('Unable to decode value');
            }
            $value .= $this->numbers[$output];
        }
        return (int) $value;
    }

    public function getNumberCount(int $number): int
    {
        if (!array_key_exists($number, $this->segments)) {
            return 0;
        }

        $pattern = $this->segments[$number];
        $values = array_count_values($this->output);
        if (array_key_exists($pattern, $values)) {
            return $values[$pattern];
        }
        return 0;
    }
}