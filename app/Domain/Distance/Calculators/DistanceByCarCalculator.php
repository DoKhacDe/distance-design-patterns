<?php

namespace App\Domain\Distance\Calculators;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;

class DistanceByCarCalculator implements DistanceCalculator
{

    public function getDistance(Point $point1, Point $point2): float
    {
        return 12.0;
    }
}
