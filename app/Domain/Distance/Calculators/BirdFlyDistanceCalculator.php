<?php

namespace App\Domain\Distance\Calculators;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;

class BirdFlyDistanceCalculator implements DistanceCalculator
{

    public function getDistance(Point $point1, Point $point2): float
    {
        //result demo
        return 10.0;
    }
}
