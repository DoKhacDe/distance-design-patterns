<?php


namespace Tests\Unit\Distance\BaiTap01;

use App\Domain\Distance\Calculators\SimpleDistanceCalculator;
use App\Domain\Distance\Point;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\TestDox;
use Tests\TestCase;

#[TestDox('Phiên bản 1')]
class SimpleDistanceCalculatorTest extends TestCase
{
    private SimpleDistanceCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new SimpleDistanceCalculator();
    }

    #[TestDox('Gọi thử hàm getDistance')]
    public function test_goi_ham_get_distance_01(): void
    {
        $distance = $this->calculator->getDistance(19.2, 34.35, 33.2, 343.35);

        $this->assertEquals(10.0, $distance);
    }

    #[TestDox('Ném exception khi vĩ độ nhỏ hơn -90')]
    public function test_nem_exception_khi_vi_do_nho_hon_gioi_han(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Point(-91, 105);
    }

    #[TestDox('Ném exception khi vĩ độ lớn hơn 90')]
    public function test_nem_exception_khi_vi_do_lon_hon_gioi_han(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Point(91, 105);
    }

    #[TestDox('Ném exception khi kinh độ nhỏ hơn -180')]
    public function test_nem_exception_khi_kinh_do_nho_hon_gioi_han(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Point(21, -181);
    }

    #[TestDox('Ném exception khi kinh độ lớn hơn 180')]
    public function test_nem_exception_khi_kinh_do_lon_hon_gioi_han(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Point(21, 181);
    }

    #[TestDox('Ném exception khi vĩ độ là giá trị vô cực')]
    public function test_nem_exception_khi_vi_do_khong_huu_han(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Point(INF, 105);
    }

    #[TestDox('Ném exception khi kinh độ là giá trị NaN')]
    public function test_nem_exception_khi_kinh_do_la_nan(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Point(21, NAN);
    }
}
