<?php

namespace App\System\DesignPatterns\Strategy\Context;

use App\System\DesignPatterns\Strategy\InterfaceAndTypePayments\PaymentStrategy;

class PaymentContext
{
    /**
     * @var PaymentStrategy
     */
    private PaymentStrategy $paymentStrategy;

    public function __construct(PaymentStrategy $strategy)
    {
        $this->paymentStrategy = $strategy;
    }

    public function pay($amount): string
    {
        return $this->paymentStrategy->pay($amount);
    }
}