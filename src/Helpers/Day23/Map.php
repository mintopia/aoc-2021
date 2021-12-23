<?php
namespace Mintopia\Aoc2021\Helpers\Day23;

use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

class Map
{
    protected array $rooms = [
        'A' => [],
        'B' => [],
        'C' => [],
        'D' => [],
    ];
    protected array $hallway;

    const HALLWAY_DOORS = [3, 5, 7, 9];

    public function __construct(array $input)
    {
        foreach ($input as $line) {
            preg_match('/^.*#(?<a>[A|B|C|D])#(?<b>[A|B|C|D])#(?<c>[A|B|C|D])#(?<d>[A|B|C|D])#.*$/', $line, $matches);
            if ($matches) {
                $this->rooms['A'][] = $matches['a'];
                $this->rooms['B'][] = $matches['b'];
                $this->rooms['C'][] = $matches['c'];
                $this->rooms['D'][] = $matches['d'];
            }
        }

        for ($i = 0; $i < 11; $i++) {
            $this->hallway[] = 'x';
        }
    }

    public function isFinished()
    {
        foreach ($this->rooms as $code => $room) {
            $unique = array_unique($room);
            if (count($unique) != 1 || $unique[0] != $code) {
                return false;
            }
        }
        return true;
    }

    public function getState()
    {
        $state = implode('', $this->hallway);
        foreach ($this->rooms as $room) {
            $state .= implode('', $room);
        }
        return $state;
    }

    public function getMoves()
    {
        // For each square in hallway and rooms, return possible moves + cost
        foreach ($this->hallway as $hi => $square) {
            $moves = [];
            if ($square === 'x') {
                // Empty square
                continue;
            }
            for ($i = $hi - 1; $i >= 0; $i--) {
                if ($this->hallway[$i] != 'x') {
                    // Occupied, we're done.
                    break;
                }
                if (!in_array($i, self::HALLWAY_DOORS)) {
                    $moves[] = $this->getCost($square) * ($hi - $i);
                    continue;
                }
                // Is this doorway our door and is it us
            }
            // Check left or right for empty space
            // If not doorway - OK
            // If doorway and our doorway, recurse down
                // If our letter and only our letter (or none) - OK
        }
    }

    public function render(OutputInterface|ConsoleSectionOutput $output)
    {
        $lines = [
            '<fg=gray>#############</>',
            '<fg=gray>#</>' . implode('', $this->hallway) . '<fg=gray>#</>',
        ];
        $pad = '<fg=gray>##</>';
        for ($i = 0; $i < count($this->rooms['A']); $i++) {
            $line = "{$pad}<fg=gray>#</>";
            foreach ($this->rooms as $room) {
                $line .= "{$room[$i]}<fg=gray>#</>";
            }
            $line .= $pad;
            $lines[] = $line;
            $pad = '  ';
        }
        $lines[] = '  <fg=gray>#########</>';
        $lines = array_map(function($line) {
            return str_replace('x', ' ', $line);
        }, $lines);
        $output->writeln($lines);
    }
}