<?php

namespace App\System\DesignPatterns\Strategy\InterfaceAndTypePayments;

interface PaymentStrategy
{
    public function pay(int $amount): string;
}