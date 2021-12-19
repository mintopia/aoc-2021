<?php
namespace Mintopia\Aoc2021\Helpers\Day19;

class Scanner
{

    public array $rotations = [];
    public ?int $rotation = null;
    public ?array $offset = null;

    public function __construct(public int $number, public array $points)
    {
        $this->calculateRotations();
    }

    protected function calculateRotations(): void
    {
        // Fun with a 3D printer calibration cube to find this mapping.
        // Every possible combination/rotation.
        $this->rotations = array_fill(0, 24, []);
        foreach ($this->points as $i => [$x, $y, $z]) {
            $this->rotations[0][$i] = [$x, $y, $z];
            $this->rotations[1][$i] = [$x, -$y, -$z];
            $this->rotations[2][$i] = [-$x, $y, -$z];
            $this->rotations[3][$i] = [-$x, -$y, $z];
            $this->rotations[4][$i] = [$x, $z, -$y];
            $this->rotations[5][$i] = [$x, -$z, $y];
            $this->rotations[6][$i] = [-$x, $z, $y];
            $this->rotations[7][$i] = [-$x, -$z, -$y];

            $this->rotations[8][$i] = [$y, $z, $x];
            $this->rotations[9][$i] = [$y, -$z, -$x];
            $this->rotations[10][$i] = [-$y, $z, -$x];
            $this->rotations[11][$i] = [-$y, -$z, $x];
            $this->rotations[12][$i] = [$y, $x, -$z];
            $this->rotations[13][$i] = [$y, -$x, $z];
            $this->rotations[14][$i] = [-$y, $x, $z];
            $this->rotations[15][$i] = [-$y, -$x, -$z];

            $this->rotations[16][$i] = [$z, $x, $y];
            $this->rotations[17][$i] = [$z, -$x, -$y];
            $this->rotations[18][$i] = [-$z, $x, -$y];
            $this->rotations[19][$i] = [-$z, -$x, $y];
            $this->rotations[20][$i] = [$z, $y, -$x];
            $this->rotations[21][$i] = [$z, -$y, $x];
            $this->rotations[22][$i] = [-$z, $y, $x];
            $this->rotations[23][$i] = [-$z, -$y, -$x];
        }
    }

    public function getOffset(array $theirPoints, int $threshold): ?array
    {
        $differences = [];
        $ourPoints = $this->getPoints();
        // Calculate the difference between every single point of ours and every single point of theirs.
        // If we have at least 12 (threshold) matching differences, then it's assumed we're a match.
        foreach ($ourPoints as [$x1, $y1, $z1]) {
            foreach ($theirPoints as [$x2, $y2, $z2]) {
                $diff = [
                    $x1 - $x2,
                    $y1 - $y2,
                    $z1 - $z2,
                ];

                $diffKey = implode(',', $diff);
                if (!isset($differences[$diffKey])) {
                    $differences[$diffKey] = 0;
                }
                $differences[$diffKey]++;

                if ($differences[$diffKey] >= $threshold) {
                    return [
                        $diff[0] + $this->offset[0],
                        $diff[1] + $this->offset[1],
                        $diff[2] + $this->offset[2],
                    ];
                }
            }
        }
        return null;
    }

    public function getPoints(): array
    {
        if ($this->rotation === null) {
            throw new \Exception('Unknown rotation');
        }
        return $this->rotations[$this->rotation];
    }

    public function getAbsolutePoints(): array
    {
        if ($this->rotation === null || $this->offset == null) {
            throw new \Exception('Unknown rotation or offset');
        }
        $normalised = [];
        foreach ($this->getPoints() as  [$x, $y, $z]) {
            $normalised[] =[$x + $this->offset[0], $y + $this->offset[1], $z + $this->offset[2]];
        }
        return $normalised;
    }
}