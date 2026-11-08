<?php

namespace App\Domain\Payment;

interface PaymentStrategy
{
    public function pay(float $amount): string;
}
