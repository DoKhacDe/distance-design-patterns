<?php

namespace App\Domain\Payment\Services;

interface PaymentService
{
    public function pay(float $amount): bool;
}
