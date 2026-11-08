<?php

namespace App\Domain\Distance;

interface DistanceHandler
{
    public function setNext(DistanceHandler $handler): DistanceHandler;

    public function handle(Distance $request): void;
}
