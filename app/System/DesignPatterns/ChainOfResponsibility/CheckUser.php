<?php

namespace App\System\DesignPatterns\ChainOfResponsibility;

abstract class CheckUser
{
    //checking user/validating user
    //throwing an exception if the validation fails
    //otherwise handing off the user to the next CheckUser validation

    /**
     * @var CheckUser
     */
    protected CheckUser $nextCheck;

    public function then(CheckUser $check)
    {
        $this->nextCheck = $check;
    }
    public abstract function check(User $user);

    public function next(User $user)
    {
        if (!isset($this->nextCheck)) return;
        $this->nextCheck->check($user);
    }
}