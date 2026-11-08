<?php

namespace App\Domain\Payment;

class Payment
{
    public function __construct(
        public string $orderId,
        public float $amount,
        public string $idempotencyKey,
    ) {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Số tiền thanh toán phải lớn hơn 0.');
        }
    }
}
