<?php

namespace Tests\Unit\Distance\BaiTap02;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\DistanceCalculator;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

#[TestDox('Phiên bản 2: Test Laravel Service Container')]
class DistanceServiceProviderTest extends TestCase
{
    #[TestDox('Container tự động resolve BirdFlyDistanceCalculator từ interface')]
    public function test_container_resolve_dung_implementation(): void
    {
        $calculator = $this->app->make(
            DistanceCalculator::class,
        );

        $this->assertInstanceOf(
            BirdFlyDistanceCalculator::class,
            $calculator,
        );
    }

    #[TestDox('Container có thể resolve calculator nhiều lần')]
    public function test_container_co_the_resolve_calculator_nhieu_lan(): void
    {
        $first = $this->app->make(DistanceCalculator::class);
        $second = $this->app->make(DistanceCalculator::class);

        $this->assertInstanceOf(
            BirdFlyDistanceCalculator::class,
            $first,
        );

        $this->assertInstanceOf(
            BirdFlyDistanceCalculator::class,
            $second,
        );

        $this->assertNotSame($first, $second);
    }
}
