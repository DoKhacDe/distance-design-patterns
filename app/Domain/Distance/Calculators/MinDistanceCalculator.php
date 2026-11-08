<?php

namespace App\Domain\Distance\Calculators;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;

class MinDistanceCalculator implements DistanceCalculator
{
    public function __construct(
        private readonly DistanceCalculator $calculator,
    ) {
    }

    public function getDistance(Point $point1, Point $point2): float
    {
        $distance = $this->calculator->getDistance($point1, $point2);

        if ($distance < 200) {
            return 200;
        }

        return $distance;
    }
}
