<?php

namespace Tests\Unit\Payment;

use App\Domain\Payment\BankPayment;
use App\Domain\Payment\CreditCardPayment;
use App\Domain\Payment\MomoPayment;
use App\Domain\Payment\PaymentGateway;
use App\Domain\Payment\Payment;
use App\Domain\Payment\PaymentResult;
use App\Domain\Payment\GatewayAPaymentHandler;
use App\Domain\Payment\GatewayBPaymentHandler;
use App\Domain\Payment\PaymentHandlerChain;
use App\Domain\Payment\PaymentStrategy;
use App\Domain\Payment\Services\LoggingPayment;
use App\Domain\Payment\Services\PaymentService;
use Mockery;
use Psr\Log\LoggerInterface;
use Tests\TestCase;

final class PaymentTest extends TestCase
{
    public function test_falls_back_to_gateway_b_when_gateway_a_fails(): void
    {
        $request = new Payment(
            orderId: 'ORDER-001',
            amount: 500_000,
            idempotencyKey: 'PAY-ORDER-001',
        );

        $gatewayA = Mockery::mock(PaymentGateway::class);
        $gatewayB = Mockery::mock(PaymentGateway::class);

        $gatewayA
            ->shouldReceive('pay')
            ->once()
            ->with($request)
            ->andReturn(new PaymentResult(
                success: false,
                retryable: true,
                message: 'Gateway A temporarily unavailable',
            ));

        $gatewayB
            ->shouldReceive('pay')
            ->once()
            ->with($request)
            ->andReturn(new PaymentResult(
                success: true,
                retryable: false,
                transactionId: 'TXN-001',
            ));

        $handlerA = new GatewayAPaymentHandler($gatewayA);
        $handlerB = new GatewayBPaymentHandler($gatewayB);

        $handlerA->setNext($handlerB);

        $chain = new PaymentHandlerChain($handlerA);

        $result = $chain->handle($request);

        $this->assertTrue($result->success);
        $this->assertSame('TXN-001', $result->transactionId);
    }

    public function test_credit_card_payment_strategy(): void
    {
        $strategy = new CreditCardPayment();

        $result = $strategy->pay(500_000);

        $this->assertSame('credit card: 500000', $result);
    }

    public function test_momo_payment_strategy(): void
    {
        $strategy = new MomoPayment();

        $result = $strategy->pay(500_000);

        $this->assertSame('momo: 500000', $result);
    }

    public function test_payment_strategies_follow_same_contract(): void
    {
        $strategies = [
            new CreditCardPayment(),
            new MomoPayment(),
            new BankPayment(),
        ];

        foreach ($strategies as $strategy) {
            $this->assertInstanceOf(PaymentStrategy::class, $strategy);

            $result = $strategy->pay(500_000);

            $this->assertIsString($result);
            $this->assertNotEmpty($result);
        }
    }

    public function test_can_switch_payment_strategy(): void
    {
        $strategies = [
            new CreditCardPayment(),
            new MomoPayment(),
        ];

        $results = [];

        foreach ($strategies as $strategy) {
            $results[] = $strategy->pay(500_000);
        }

        $this->assertSame([
            'credit card: 500000',
            'momo: 500000',
        ], $results);
    }

    public function test_logging_payment_logs_before_and_after_successful_payment(): void
    {
        $amount = 500_000.0;

        $payment = Mockery::mock(PaymentService::class);

        $payment
            ->shouldReceive('pay')
            ->once()
            ->with($amount)
            ->andReturn(true);

        $logger = Mockery::mock(LoggerInterface::class);

        $logger
            ->shouldReceive('info')
            ->once()
            ->with('Bắt đầu thanh toán: 500000');

        $logger
            ->shouldReceive('info')
            ->once()
            ->with('Kết quả thanh toán: SUCCESS');

        $decorator = new LoggingPayment($payment, $logger);

        $result = $decorator->pay($amount);

        $this->assertTrue($result);
    }

    public function test_logging_payment_logs_failed_payment(): void
    {
        $amount = 500_000.0;

        $payment = Mockery::mock(PaymentService::class);

        $payment
            ->shouldReceive('pay')
            ->once()
            ->with($amount)
            ->andReturn(false);

        $logger = Mockery::mock(LoggerInterface::class);

        $logger
            ->shouldReceive('info')
            ->once()
            ->with('Bắt đầu thanh toán: 500000');

        $logger
            ->shouldReceive('info')
            ->once()
            ->with('Kết quả thanh toán: FAILED');

        $decorator = new LoggingPayment($payment, $logger);

        $result = $decorator->pay($amount);

        $this->assertFalse($result);
    }

    public function test_logging_payment_returns_original_payment_result(): void
    {
        $payment = Mockery::mock(PaymentService::class);

        $payment
            ->shouldReceive('pay')
            ->once()
            ->andReturn(true);

        $logger = Mockery::mock(LoggerInterface::class);

        $logger
            ->shouldReceive('info')
            ->twice();

        $decorator = new LoggingPayment($payment, $logger);

        $this->assertSame(true, $decorator->pay(100_000.0));
    }
}
