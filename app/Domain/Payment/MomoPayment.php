<?php

namespace App\Domain\Payment;

use App\Domain\Payment\PaymentStrategy;

class MomoPayment implements PaymentStrategy
{

    public function pay(float $amount): string
    {
        return "momo: {$amount}";
    }
}
