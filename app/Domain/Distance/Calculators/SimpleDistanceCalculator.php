<?php

namespace App\Domain\Distance\Calculators;

final class SimpleDistanceCalculator
{
    public function getDistance(float $lat1, float $long1, float $lat2, float $long2): float
    {
        // Tính khoảng cách đường chim bay
        // (công thức Haversine hoặc công thức Vincenty)
        return 10.0; // Đây là kết quả giả định
    }
}
