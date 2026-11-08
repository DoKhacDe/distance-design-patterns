<?php

namespace Tests\Unit\Distance\BaiTap04;

use App\Domain\Distance\Calculators\BirdFlyDistanceCalculator;
use App\Domain\Distance\Distance;
use App\Domain\Distance\Point;
use App\Domain\Distance\Exceptions\DistanceException;
use App\Domain\Distance\CoordinateValidationHandler;
use App\Domain\Distance\DistanceCalculationHandler;
use App\Domain\Distance\DistanceHandlerChain;
use App\Domain\Distance\DistanceLimitHandler;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[TestDox('Phiên bản 04 - Chain of Responsibility')]
final class DistanceHandlerChainTest extends TestCase
{
    private function createRequest(): Distance
    {
        return new Distance(
            new Point(21.0285, 105.8542),
            new Point(10.8231, 106.6297),
        );
    }

    #[TestDox('Tính khoảng cách thành công khi nằm trong giới hạn')]
    public function test_calculate_distance_successfully(): void
    {
        $chain = new DistanceHandlerChain(
            calculator: new BirdFlyDistanceCalculator(),
            maximumDistanceKm: 100,
        );

        $request = $this->createRequest();

        $chain->handle($request);

        $this->assertSame(10.0, $request->getDistance());
    }

    #[TestDox('Ném exception khi khoảng cách vượt quá giới hạn')]
    public function test_throw_exception_when_distance_exceeds_limit(): void
    {
        $chain = new DistanceHandlerChain(
            calculator: new BirdFlyDistanceCalculator(),
            maximumDistanceKm: 5,
        );

        $request = $this->createRequest();

        $this->expectException(DistanceException::class);
        $this->expectExceptionMessage('Khoảng cách vượt quá giới hạn.');

        $chain->handle($request);
    }

    #[TestDox('Ném exception khi cấu hình giới hạn không hợp lệ')]
    public function test_throw_exception_when_maximum_distance_is_invalid(): void
    {
        $chain = new DistanceHandlerChain(
            calculator: new BirdFlyDistanceCalculator(),
            maximumDistanceKm: 20_016,
        );

        $request = $this->createRequest();

        $this->expectException(DistanceException::class);
        $this->expectExceptionMessage('Giới hạn khoảng cách không hợp lệ.');

        $chain->handle($request);
    }

    #[TestDox('Validation handler chuyển request sang handler tiếp theo')]
    public function test_validation_handler_passes_request_to_next_handler(): void
    {
        $request = $this->createRequest();

        $calculationHandler = new DistanceCalculationHandler(
            calculator: new BirdFlyDistanceCalculator(),
            maximumDistanceKm: 100,
        );

        $validationHandler = new CoordinateValidationHandler();
        $validationHandler->setNext($calculationHandler);

        $validationHandler->handle($request);

        $this->assertSame(10.0, $request->getDistance());
    }

    #[TestDox('DistanceLimitHandler từ chối cấu hình giới hạn không hợp lệ')]
    public function test_distance_limit_handler_rejects_invalid_configuration(): void
    {
        $handler = new DistanceLimitHandler(20_016);

        $request = $this->createRequest();

        $this->expectException(DistanceException::class);
        $this->expectExceptionMessage('Giới hạn khoảng cách không hợp lệ.');

        $handler->handle($request);
    }
}
