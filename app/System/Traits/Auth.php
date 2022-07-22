<?php

namespace App\System\Traits;

trait Auth
{
    public function isLoggedIn()
    {
        return !empty($_SESSION['user'] && !empty($_SESSION['loggedIn']));
    }

    public function getUser()
    {
        return $this->isLoggedIn() ? $_SESSION['user']['username'] : '';
    }

}