<?php

namespace Tests\Unit\Distance\BaiTap03;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\Calculators\DistanceCalculatorResolver;
use App\Domain\Distance\Calculators\DistanceByCarCalculator;
use App\Domain\Distance\Enums\DistanceStrategy;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[TestDox('Phiên bản 03: Test Strategy')]
final class DistanceStrategyTest extends TestCase
{
    private DistanceCalculatorResolver $resolver;

    protected function setUp(): void
    {
        $this->resolver = new DistanceCalculatorResolver(
            new BirdFlyDistanceCalculator(),
            new DistanceByCarCalculator(),
        );
    }

    #[TestDox('Chọn BirdFlyDistanceCalculator khi strategy là bird_fly')]
    public function test_resolves_bird_fly_strategy(): void
    {
        $calculator = $this->resolver->resolve(
            DistanceStrategy::BIRD_FLY,
        );

        self::assertInstanceOf(BirdFlyDistanceCalculator::class, $calculator);
    }

    #[TestDox('Chọn ManhattanDistanceCalculator khi strategy là car')]
    public function test_resolves_car_strategy(): void
    {
        $calculator = $this->resolver->resolve(
            DistanceStrategy::CAR,
        );

        self::assertInstanceOf(DistanceByCarCalculator::class, $calculator);
    }
}
