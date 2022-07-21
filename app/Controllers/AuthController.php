<?php

namespace App\Controllers;

use App\System\AbstractController;

class AuthController extends AbstractController
{
    public function index()
    {
        echo 'tva e avtora be brat';
    }

    public function login()
    {
        echo 'da be tova e avtora';

        $this->renderView('auth/login', ['error' => 'error']);
    }
}