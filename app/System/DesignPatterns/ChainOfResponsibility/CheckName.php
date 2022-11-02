<?php

namespace App\System\DesignPatterns\ChainOfResponsibility;

class CheckName extends CheckUser
{

    public function check(User $user)
    {
        if (!$user->name) throw new \Exception('User Name Not Found. ABORT!!!');
        $this->next($user);
    }
}