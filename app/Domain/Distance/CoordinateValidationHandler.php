<?php

namespace App\Domain\Distance;

use App\Domain\Distance\Exceptions\DistanceException;

final class CoordinateValidationHandler extends AbstractDistanceHandler
{
    public function handle(Distance $request): void
    {
        $points = [$request->point1, $request->point2];

        foreach ($points as $point) {
            if ($point->lat < -90 || $point->lat > 90) {
                throw new DistanceException('Vĩ độ không hợp lệ.');
            }

            if ($point->long < -180 || $point->long > 180) {
                throw new DistanceException('Kinh độ không hợp lệ.');
            }
        }

        $this->next($request);
    }
}
