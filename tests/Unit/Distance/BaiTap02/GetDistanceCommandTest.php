<?php


namespace Tests\Unit\Distance\BaiTap02;

use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

#[TestDox('Phiên bản 02: Test Get Distance Command')]
class GetDistanceCommandTest extends TestCase
{
    #[TestDox('Test gọi command Get Distance Command')]
    public function test_command_get_distance_command(): void
    {
        $calculator = $this->createMock(DistanceCalculator::class);

        $calculator
            ->expects($this->once())
            ->method('getDistance')
            ->willReturn(10.0);

        $this->app->instance(
            DistanceCalculator::class,
            $calculator,
        );

        $this->artisan('distance:get')
            ->expectsOutput('Result: 10')
            ->assertExitCode(0);
    }
}
