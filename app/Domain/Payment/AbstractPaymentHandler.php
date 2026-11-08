<?php

namespace App\Domain\Payment;

abstract class AbstractPaymentHandler implements PaymentHandler
{
    private ?PaymentHandler $nextHandler = null;

    public function setNext(PaymentHandler $handler): PaymentHandler
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    abstract public function handle(Payment $request): PaymentResult;

    protected function next(Payment $request): PaymentResult
    {
        if ($this->nextHandler === null) {
            return new PaymentResult(
                success: false,
                retryable: false,
                message: 'Tất cả cổng thanh toán đều thất bại.',
            );
        }

        return $this->nextHandler->handle($request);
    }
}
