<?php

namespace App\Domain\Payment;

use App\Domain\Payment\PaymentStrategy;

class BankPayment implements PaymentStrategy
{

    public function pay(float $amount): string
    {
        return "bank: {$amount}";
    }
}
