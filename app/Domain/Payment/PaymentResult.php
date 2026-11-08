<?php

namespace App\Domain\Payment;

class PaymentResult
{
    public function __construct(
        public bool $success,
        public bool $retryable,
        public ?string $transactionId = null,
        public ?string $message = null,
    ) {
    }
}
