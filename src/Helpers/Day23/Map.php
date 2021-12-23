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

    // Hallway is 1-11
    // Rooms are A = 12, B = 13, C = 14, D = 15, A2 = 16

    const HALLWAY_DOORS = [
        2 => 'A',
        4 => 'B',
        6 => 'C',
        8 => 'D',
    ];

    const ROOM_HALLWAY = [
        'A' => 2,
        'B' => 4,
        'C' => 6,
        'D' => 8,
    ];

    const ENERGY = [
        'A' => 1,
        'B' => 10,
        'C' => 100,
        'D' => 1000,
    ];

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

    public function getMoves(): \SplPriorityQueue
    {
        $allMoves = new \SplPriorityQueue();
        // For each square in hallway and rooms, return possible moves + cost
        foreach ($this->hallway as $hi => $amphipod) {
            $moves = [];
            if ($amphipod === 'x') {
                // Empty square
                continue;
            }
            $moves = $this->getHallwayMoves($hi, $amphipod);
            foreach ($moves as $to => $cost) {
                $allMoves->insert((object) [
                    'from' => $hi,
                    'to' => $to,
                    'cost' => $cost
                ], -$cost);
            }
        }

        foreach ($this->rooms as $letter => $room) {
            foreach ($room as $ri => $amphipod) {
                if ($amphipod === 'x') {
                    // Empty Square
                    continue;
                }
                $door = self::ROOM_HALLWAY[$letter];
                $moves = $this->getHallwayMoves($door, $amphipod);
                $extraCost = $this->getCost($amphipod) * ($ri + 1);
                foreach ($moves as $to => $cost) {
                    $cost += $extraCost;
                    $allMoves->insert((object) [
                        'from' => $door . $ri,
                        'to' => $to,
                        'cost' => $cost,
                    ], -$cost);
                }
                // No more in this room can move
                break;
            }
        }

        return $allMoves;
    }

    public function move(int $from, int $to): void
    {
        if ($from <= 10) {
            $value = $this->hallway[$from];
            $this->hallway[$from] = 'x';
        } else {
            [$room, $position] = str_split((string) $from);
            $value = $this->rooms[self::HALLWAY_DOORS[$room]][$position];
            $this->rooms[self::HALLWAY_DOORS[$room]][$position] = 'x';
        }

        if ($to <= 10) {
            $this->hallway[$to] = $value;
        } else {
            [$room, $position] = str_split((string) $to);
            $this->rooms[self::HALLWAY_DOORS[$room]][$position] = $value;
        }
    }

    protected function getHallwayMoves(int $hi, string $amphipod): array
    {
        $left = $this->getHallwayMovesDirection($hi, $amphipod, $hi - 1, -1, -1);
        $right = $this->getHallwayMovesDirection($hi, $amphipod, $hi + 1, count($this->hallway), 1);
        return $left + $right;
    }

    protected function getHallwayMovesDirection(int $hi, string $amphipod, int $from, int $to, int $adjustment): array
    {
        $moves = [];
        $cost = $this->getCost($amphipod);
        for ($i = $from; $i != $to; $i += $adjustment) {
            if ($this->hallway[$i] != 'x') {
                // Occupied, we're done.
                break;
            }
            if (!array_key_exists($i, self::HALLWAY_DOORS)) {
                $moves[$i] = $this->getCost($amphipod) * abs($hi - $i);
                continue;
            }
            // Is this doorway our door and is it us
            $door = self::HALLWAY_DOORS[$i];
            if ($door != $amphipod) {
                // It is not us, no valid moves here
                continue;
            }
            // It is us! If it's empty or only us, then we can have it as a move!
            $possible = [];
            foreach ($this->rooms[$door] as $di => $square) {
                $location = $i . $di;
                if ($square == 'x') {
                    $possible[$location] = $cost * $i * ($di + 1);
                    continue;
                }
                if ($square != $amphipod) {
                    $possible = [];
                }
                break;
            }
            $moves = $moves + $possible;
        }
        return $moves;
    }

    protected function getCost(string $amphipod): int
    {
        if (array_key_exists($amphipod, self::ENERGY)) {
            return self::ENERGY[$amphipod];
        }
        return 0;
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