<?php

namespace App\System\DesignPatterns\ChainOfResponsibility;

use PHPUnit\Framework\TestCase;

class ChainTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testEverything()
    {
        $user = new User();

        $checkId = new CheckId();
        $checkName = new CheckName();
        $checkEmail = new CheckEmail();

        $checkEmail->then($checkId);
        $checkId->then($checkName);

        $myException = '';
        try {
            $checkEmail->check($user);
        } catch (\Exception $e) {
            $myException = $e->getMessage();
        }

        $this->assertEquals('first', $myException);
    }
}