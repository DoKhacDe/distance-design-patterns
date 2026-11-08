<?php

namespace App\Providers;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\DistanceCalculator;
use Illuminate\Support\ServiceProvider;

class DistanceServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(DistanceCalculator::class, BirdFlyDistanceCalculator::class);
    }
}
