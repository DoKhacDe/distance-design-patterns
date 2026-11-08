<?php

namespace App\Domain\Distance;

use App\Domain\Distance\Distance;
use App\Domain\Distance\Exceptions\DistanceException;

final class DistanceLimitHandler extends AbstractDistanceHandler
{
    public function __construct(
        private readonly float $maximumDistanceKm,
    ) {
    }

    public function handle(Distance $request): void
    {
        $maximumPossibleDistance = 20_015.1;

        if ($this->maximumDistanceKm > $maximumPossibleDistance) {
            throw new DistanceException('Giới hạn khoảng cách không hợp lệ.');
        }

        $this->next($request);
    }
}
