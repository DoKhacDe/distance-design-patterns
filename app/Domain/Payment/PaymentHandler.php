<?php

namespace App\Domain\Payment;

interface PaymentHandler
{
    public function setNext(PaymentHandler $handler): PaymentHandler;

    public function handle(Payment $request): PaymentResult;
}
