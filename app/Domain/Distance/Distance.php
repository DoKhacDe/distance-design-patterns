<?php

namespace App\Domain\Distance;

class Distance
{
    private ?float $distance = null;

    public function __construct(
        public readonly Point $point1,
        public readonly Point $point2,
    ) {
    }

    public function setDistance(float $distance): void
    {
        $this->distance = $distance;
    }

    public function getDistance(): ?float
    {
        return $this->distance;
    }
}
