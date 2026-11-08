<?php

namespace App\Domain\Payment;

class PaymentHandlerChain
{
    public function __construct(
        private readonly PaymentHandler $firstHandler,
    ) {}

    public function handle(Payment $request): PaymentResult
    {
        return $this->firstHandler->handle($request);
    }
}
