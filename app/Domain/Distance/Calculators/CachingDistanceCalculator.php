<?php

namespace App\Domain\Distance\Calculators;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CachingDistanceCalculator implements DistanceCalculator
{
    public function __construct(
        private readonly DistanceCalculator $calculator,
    ) {
    }

    public function getDistance(Point $point1, Point $point2): float
    {
        $keyCache = $this->getKeyCache($point1, $point2);
        $distance = Cache::get($keyCache);
        if ($distance > 0) {
            return $distance;
        }
        $distance = $this->calculator->getDistance($point1, $point2);
        Cache::put($keyCache, $distance, Carbon::now()->addWeek());

        return $distance;
    }

    private function getKeyCache(Point $point1, Point $point2): string
    {
        return $point1->getLat() . '-' . $point1->getLong() . ':' . $point2->getLat() . '-' . $point2->getLong();
    }
}
