<?php

namespace App\System\Traits;

trait Auth
{
    public function isLoggedIn()
    {
        return !empty($_SESSION['user'] && !empty($_SESSION['loggedIn']));
    }

}