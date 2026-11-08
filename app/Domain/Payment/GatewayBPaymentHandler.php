<?php

namespace App\Domain\Payment;

class GatewayBPaymentHandler extends AbstractPaymentHandler
{
    public function __construct(
        private readonly PaymentGateway $gateway,
    ) {
    }

    public function handle(Payment $request): PaymentResult
    {
        $result = $this->gateway->pay($request);

        if ($result->success) {
            return $result;
        }

        if (!$result->retryable) {
            return $result;
        }

        return $this->next($request);
    }
}
