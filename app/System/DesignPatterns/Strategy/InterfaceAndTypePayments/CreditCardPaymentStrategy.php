<?php

namespace App\System\DesignPatterns\Strategy\InterfaceAndTypePayments;

class CreditCardPaymentStrategy implements PaymentStrategy
{
    /**
     * @param int $amount
     * @return string
     */
    public function pay(int $amount): string
    {
        return 'This is credit card payment = ' . $amount . 'lv';
    }
}