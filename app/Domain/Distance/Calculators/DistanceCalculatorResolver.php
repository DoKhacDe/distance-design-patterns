<?php

namespace App\Domain\Distance\Calculators;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Enums\DistanceStrategy;

class DistanceCalculatorResolver
{
    public function __construct(
        private BirdFlyDistanceCalculator $birdFlyCalculator,
        private DistanceByCarCalculator $carCalculator,
    ) {
    }

    public function resolve(DistanceStrategy $strategy): DistanceCalculator
    {
        return match ($strategy) {
            DistanceStrategy::BIRD_FLY => $this->birdFlyCalculator,
            DistanceStrategy::CAR => $this->carCalculator,
        };
    }
}
