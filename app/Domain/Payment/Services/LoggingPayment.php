<?php

namespace App\Domain\Payment\Services;

use Psr\Log\LoggerInterface;

class LoggingPayment implements PaymentService
{
    public function __construct(
        private readonly PaymentService $payment,
        private readonly LoggerInterface $logger,
    ) {}

    public function pay(float $amount): bool
    {
        $this->logger->info("Bắt đầu thanh toán: {$amount}");

        $result = $this->payment->pay($amount);

        $this->logger->info(
            'Kết quả thanh toán: ' . ($result ? 'SUCCESS' : 'FAILED')
        );

        return $result;
    }
}
