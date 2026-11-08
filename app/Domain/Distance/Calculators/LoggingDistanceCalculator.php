<?php

namespace App\Domain\Distance\Calculators;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use Psr\Log\LoggerInterface;

class LoggingDistanceCalculator implements DistanceCalculator
{
    public function __construct(
        private readonly DistanceCalculator $calculator,
        private readonly LoggerInterface $logger
    ) {}

    public function getDistance(Point $point1, Point $point2): float
    {
        $this->logger->info("Tính khoảng cách từ ({$point1->getLat()},{$point1->getLong()}) đến ({$point2->getLat()},{$point2->getLong()})");
        $distance = $this->calculator->getDistance($point1, $point2);
        $this->logger->info("Kết quả: {$distance}");
        return $distance;
    }
}
