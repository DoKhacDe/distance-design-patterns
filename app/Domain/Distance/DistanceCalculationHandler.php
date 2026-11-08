<?php

declare(strict_types=1);

namespace App\Domain\Distance;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\Distance;
use App\Domain\Distance\Exceptions\DistanceException;

final class DistanceCalculationHandler extends AbstractDistanceHandler
{
    public function __construct(
        private readonly BirdFlyDistanceCalculator $calculator,
        private readonly float $maximumDistanceKm,
    ) {
    }

    public function handle(Distance $request): void
    {
        $distance = $this->calculator->getDistance(
            $request->point1,
            $request->point2,
        );

        if ($distance > $this->maximumDistanceKm) {
            throw new DistanceException('Khoảng cách vượt quá giới hạn.');
        }

        $request->setDistance($distance);
    }
}
