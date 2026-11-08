<?php


namespace Tests\Unit\Distance\BaiTap05;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\Calculators\DistanceByCarCalculator;
use App\Domain\Distance\Point;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[TestDox('Phiên bản 05 - Template Method Pattern')]
final class TemplateMethodTest extends TestCase
{
    private function createPoints(): array
    {
        return [
            new Point(21.0285, 105.8542),
            new Point(10.8231, 106.6297),
        ];
    }

    #[TestDox('BirdFly Calculator trả về kết quả đã được làm tròn')]
    public function test_bird_fly_calculator_returns_formatted_distance(): void
    {
        [$point1, $point2] = $this->createPoints();

        $calculator = new BirdFlyDistanceCalculator();

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(10.0, $distance);
    }

    #[TestDox('Car Calculator trả về kết quả đã được làm tròn')]
    public function test_car_calculator_returns_formatted_distance(): void
    {
        [$point1, $point2] = $this->createPoints();

        $calculator = new DistanceByCarCalculator();

        $distance = $calculator->getDistance($point1, $point2);

        $this->assertSame(12.0, $distance);
    }

    #[TestDox('Các calculator đều sử dụng chung quy trình tính khoảng cách')]
    public function test_calculators_follow_the_same_template(): void
    {
        [$point1, $point2] = $this->createPoints();

        $calculators = [
            new BirdFlyDistanceCalculator(),
            new DistanceByCarCalculator(),
        ];

        foreach ($calculators as $calculator) {
            $distance = $calculator->getDistance($point1, $point2);

            $this->assertIsFloat($distance);
            $this->assertGreaterThan(0, $distance);
        }
    }
}
