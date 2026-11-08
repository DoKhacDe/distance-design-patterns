<?php

namespace Tests\Unit\Distance\BaiTap06;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\Calculators\CachingDistanceCalculator;
use App\Domain\Distance\Calculators\LoggingDistanceCalculator;
use App\Domain\Distance\Calculators\MinDistanceCalculator;
use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Cache;
use Mockery;
use PHPUnit\Framework\Attributes\TestDox;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

#[TestDox('Phiên bản 06 - Decorator Pattern')]
final class DistanceCalculatorDecoratorTest extends TestCase
{
    private function createPoints(): array
    {
        return [
            new Point(21.0285, 105.8542),
            new Point(10.8231, 106.6297),
        ];
    }

    private function getExpectedCacheKey(): string
    {
        return '21.0285-105.8542:10.8231-106.6297';
    }

    #[TestDox('MinDistance Calculator trả về khoảng cách tối thiểu 200 km')]
    public function test_min_distance_returns_minimum_distance(): void
    {
        [$point1, $point2] = $this->createPoints();

        $calculator = new MinDistanceCalculator(
            new BirdFlyDistanceCalculator(),
        );

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(200.0, $distance);
    }

    #[TestDox('Logging Calculator ghi log và trả về kết quả tính khoảng cách')]
    public function test_logging_calculator_logs_distance_calculation(): void
    {
        [$point1, $point2] = $this->createPoints();

        $innerCalculator = Mockery::mock(DistanceCalculator::class);

        $innerCalculator
            ->shouldReceive('getDistance')
            ->once()
            ->with($point1, $point2)
            ->andReturn(123.45);

        $logger = Mockery::mock(LoggerInterface::class);

        $logger
            ->shouldReceive('info')
            ->once()
            ->with(
                'Tính khoảng cách từ (21.0285,105.8542) đến (10.8231,106.6297)'
            );

        $logger
            ->shouldReceive('info')
            ->once()
            ->with('Kết quả: 123.45');

        $calculator = new LoggingDistanceCalculator(
            $innerCalculator,
            $logger,
        );

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(123.45, $distance);
    }

    #[TestDox('Caching Calculator tính toán và lưu kết quả khi cache chưa tồn tại')]
    public function test_caching_calculator_stores_distance_in_cache(): void
    {
        [$point1, $point2] = $this->createPoints();

        $innerCalculator = Mockery::mock(DistanceCalculator::class);

        $innerCalculator
            ->shouldReceive('getDistance')
            ->once()
            ->with($point1, $point2)
            ->andReturn(10.0);

        Cache::shouldReceive('get')
            ->once()
            ->with($this->getExpectedCacheKey())
            ->andReturn(null);

        Cache::shouldReceive('put')
            ->once()
            ->withArgs(function (
                $key,
                $distance,
                $expiration,
            ): bool {
                return $key === $this->getExpectedCacheKey()
                    && $distance === 10.0
                    && $expiration instanceof CarbonInterface;
            })
            ->andReturn(true);

        $calculator = new CachingDistanceCalculator($innerCalculator);

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(10.0, $distance);
    }

    #[TestDox('Caching Calculator trả về kết quả đã có trong cache')]
    public function test_caching_calculator_returns_cached_distance(): void
    {
        [$point1, $point2] = $this->createPoints();

        $innerCalculator = Mockery::mock(DistanceCalculator::class);

        $innerCalculator
            ->shouldNotReceive('getDistance');

        Cache::shouldReceive('get')
            ->once()
            ->with($this->getExpectedCacheKey())
            ->andReturn(10.0);

        Cache::shouldNotReceive('put');

        $calculator = new CachingDistanceCalculator($innerCalculator);

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(10.0, $distance);
    }

    #[TestDox('Có thể kết hợp Caching và MinDistance Calculator')]
    public function test_can_combine_multiple_decorators(): void
    {
        [$point1, $point2] = $this->createPoints();

        Cache::shouldReceive('get')
            ->once()
            ->with($this->getExpectedCacheKey())
            ->andReturn(null);

        Cache::shouldReceive('put')
            ->once()
            ->withArgs(function (
                $key,
                $distance,
                $expiration,
            ): bool {
                return $key === $this->getExpectedCacheKey()
                    && $distance === 200.0
                    && $expiration instanceof CarbonInterface;
            })
            ->andReturn(true);

        $calculator = new CachingDistanceCalculator(
            new MinDistanceCalculator(
                new BirdFlyDistanceCalculator(),
            ),
        );

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(200.0, $distance);
    }
}
