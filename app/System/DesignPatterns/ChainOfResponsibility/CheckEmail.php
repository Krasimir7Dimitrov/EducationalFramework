<?php

namespace App\System\DesignPatterns\ChainOfResponsibility;

class CheckEmail extends CheckUser
{

    public function check(User $user)
    {
        if (!$user->email) throw new \Exception('User Email Not Found. ABORT!!!');
        $this->next($user);
    }
}