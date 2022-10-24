<?php

namespace App\System\DesignPatterns\Strategy;

use App\System\DesignPatterns\Strategy\Context\PaymentContext;
use App\System\DesignPatterns\Strategy\InterfaceAndTypePayments\BankPaymentStrategy;
use PHPUnit\Framework\TestCase;

class TestStrategy extends TestCase
{
    public function testBankPayment()
    {
        $context = new PaymentContext(new BankPaymentStrategy());
        $result = $context->pay(15);
        $this->assertEquals('This is bank payment = 15lv', $result);
    }
}