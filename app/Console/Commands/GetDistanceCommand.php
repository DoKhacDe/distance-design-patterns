<?php

namespace App\Console\Commands;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use Illuminate\Console\Command;
class GetDistanceCommand extends Command
{
    protected $signature = 'distance:get';

    // Laravel sẽ tự động inject DistanceCalculator vào handle method
    public function handle(DistanceCalculator $calculator): void
    {
        $this->info('start');

        $point1 = new Point(19.2, 89.35);
        $point2 = new Point(89.2, 107.35);

        $distance = $calculator->getDistance($point1, $point2);

        $this->info('Result: ' . $distance);
    }
}
