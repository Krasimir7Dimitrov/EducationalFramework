<?php

namespace App\System\DesignPatterns\Strategy\InterfaceAndTypePayments;

class BankPaymentStrategy implements PaymentStrategy
{
    /**
     * @param int $amount
     * @return string
     */
    public function pay(int $amount): string
    {
        return 'This is bank payment = ' . $amount . 'lv';
    }
}