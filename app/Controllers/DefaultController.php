<?php

namespace App\Controllers;

use App\System\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        $this->renderView('default/index', []);
    }
}