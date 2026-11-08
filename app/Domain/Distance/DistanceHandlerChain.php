<?php

namespace App\Domain\Distance;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\Distance;

final class DistanceHandlerChain
{
    private CoordinateValidationHandler $validationHandler;

    public function __construct(
        BirdFlyDistanceCalculator $calculator,
        float $maximumDistanceKm = 20_000.0,
    ) {
        $this->validationHandler = new CoordinateValidationHandler();

        $distanceLimitHandler = new DistanceLimitHandler(
            $maximumDistanceKm,
        );

        $calculationHandler = new DistanceCalculationHandler(
            $calculator,
            $maximumDistanceKm,
        );

        $this->validationHandler
            ->setNext($distanceLimitHandler)
            ->setNext($calculationHandler);
    }

    public function handle(Distance $request): void
    {
        $this->validationHandler->handle($request);
    }
}
