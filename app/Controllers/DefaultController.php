<?php

namespace App\Controllers;

use App\System\AbstractController;

class DefaultController extends AbstractController
{
    public function index()
    {
        var_dump('This is the index method of the DefaultCOntroller');
    }
}