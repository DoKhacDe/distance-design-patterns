<?php

namespace App\Domain\Payment;

use App\Domain\Payment\PaymentStrategy;

class CreditCardPayment implements PaymentStrategy
{

    public function pay(float $amount): string
    {
        return "credit card: {$amount}";
    }
}
