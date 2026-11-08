<?php

namespace App\Domain\Payment;

interface PaymentGateway
{
    public function pay(Payment $request): PaymentResult;
}
