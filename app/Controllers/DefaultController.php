<?php

namespace App\Controllers;

use App\System\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {

        var_dump($_SESSION);
        $this->renderView('default/index', []);
    }
}