<?php

namespace App\System\DesignPatterns\ChainOfResponsibility;

class CheckId extends CheckUser
{

    public function check(User $user)
    {
        if (!$user->id) throw new \Exception('User Id Not Found. ABORT!!!');
        $this->next($user);
    }
}