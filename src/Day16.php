<?php
namespace Mintopia\Aoc2021;

use Mintopia\Aoc2021\Helpers\Day16\Packet;
use Mintopia\Aoc2021\Helpers\Result;

class Day16 extends Day
{
    protected Packet $packet;

    protected function loadData(): void
    {
        $data = file_get_contents($this->getInputFilename());
        $this->packet = $this->parse(str_split($data));
    }

    protected function part1(): Result
    {
        return new Result(Result::PART1, $this->packet->sumVersion());
    }

    protected function part2(Result $part1): Result
    {
        return new Result(Result::PART2, $this->packet->exec());
    }

    protected function parse(array $data): Packet
    {
        $bin = array_reduce($data, function (string $carry, string $char) {
            $binary = base_convert($char, 16, 2);
            return $carry . sprintf("%04d", $binary);
        }, '');
        $packets = $this->getPackets($bin);
        return $packets->packets[0];
    }

    protected function getPackets(string $bin, ?int $packetLimit = null): object
    {
        $packets = [];
        $i = 0;
        while ($i < strlen($bin)) {
            $packet = new Packet();
            $packet->version = bindec(substr($bin, $i, 3));
            $i += 3;
            $packet->type = bindec(substr($bin, $i, 3));
            $i += 3;
            if ($packet->type == Packet::TYPE_SCALAR) {
                $binValue = '';
                do {
                    $first = substr($bin, $i, 1);
                    $binValue .= substr($bin, $i + 1, 4);
                    $i += 5;

                } while ($first == 1);
                $packet->value = bindec($binValue);
            } else {
                if (substr($bin, $i, 1) == 0) {
                    $length = bindec(substr($bin, $i + 1, 15));
                    $subPackets = $this->getPackets(substr($bin, $i + 16, $length));
                    $i += 16 + $length;
                } else {
                    $number = bindec(substr($bin, $i + 1, 11));
                    $subPackets = $this->getPackets(substr($bin, $i + 12), $number);
                    $i += 12 + $subPackets->bitsRead;
                }
                $packet->subPackets = $subPackets->packets;
            }
            $packets[] = (object) $packet;
            if ($packetLimit && count($packets) == $packetLimit) {
                break;
            }
        }
        return (object) [
            'packets' => $packets,
            'bitsRead' => $i,
        ];
    }
}