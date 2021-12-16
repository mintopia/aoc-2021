<?php
namespace Mintopia\Aoc2021\Helpers\Day16;

class Packet
{
    const TYPE_SUM = 0;
    const TYPE_PRODUCT = 1;
    const TYPE_MIN = 2;
    const TYPE_MAX = 3;
    const TYPE_SCALAR = 4;
    const TYPE_GT = 5;
    const TYPE_LT = 6;
    const TYPE_EQ = 7;

    public array $subPackets = [];
    public int $type;
    public int $value = 0;
    public int $version = 0;

    public function sumVersion()
    {
        $sum = array_reduce($this->subPackets, function(int $carry, Packet $packet) {
            return $carry + $packet->sumVersion();
        }, 0);
        return $sum + $this->version;
    }

    public function exec(): int
    {
        $values = array_map(function(Packet $packet) {
            return $packet->exec();
        }, $this->subPackets);

        switch ($this->type) {
            case self::TYPE_SUM:
                return array_sum($values);
            case self::TYPE_PRODUCT:
                return array_product($values);
            case self::TYPE_MIN:
                return min($values);
            case self::TYPE_MAX:
                return max($values);
            case self::TYPE_SCALAR:
                return $this->value;
            case self::TYPE_GT:
                return (int) $values[0] > $values[1];
            case self::TYPE_LT:
                return (int) $values[1] > $values[0];
            case self::TYPE_EQ:
                return (int) $values[0] == $values[1];
            default:
                return 0;
        }
    }
}