<?php

namespace Tests\Unit\Distance\BaiTap02;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\DistanceCalculator;
use App\Domain\Distance\Point;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

#[TestDox('Phiên bản 2: Test BirdFlyDistanceCalculator')]
class BirdFlyDistanceCalculatorTest extends TestCase
{
    private BirdFlyDistanceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new BirdFlyDistanceCalculator();
    }

    #[TestDox('BirdFlyDistanceCalculator phải triển khai DistanceCalculator')]
    public function test_calculator_phai_implement_distance_calculator(): void
    {
        $this->assertInstanceOf(
            DistanceCalculator::class,
            $this->calculator,
        );
    }

    #[TestDox('Khoảng cách đường chim bay')]
    public function test_khoang_cach_duong_chim_bay(): void
    {
        $point = new Point(21.0285, 105.8542);

        $distance = $this->calculator->getDistance(
            $point,
            $point,
        );

        $this->assertEquals(10.0, $distance);
    }
}
