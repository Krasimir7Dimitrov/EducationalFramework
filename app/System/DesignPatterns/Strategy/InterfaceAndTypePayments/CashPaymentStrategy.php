<?php

namespace App\System\DesignPatterns\Strategy\InterfaceAndTypePayments;

class CashPaymentStrategy implements PaymentStrategy
{
    /**
     * @param int $amount
     * @return string
     */
    public function pay(int $amount): string
    {
        return 'This is cash payment = ' . $amount . 'lv';
    }
}