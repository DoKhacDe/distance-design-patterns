<?php

namespace App\Domain\Distance;

abstract class AbstractDistanceHandler implements DistanceHandler
{
    private ?DistanceHandler $nextHandler = null;

    public function setNext(DistanceHandler $handler): DistanceHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    protected function next(Distance $request): void
    {
        $this->nextHandler?->handle($request);
    }
}
