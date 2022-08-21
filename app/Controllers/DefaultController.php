<?php

namespace App\Controllers;

use App\System\AbstractController;
use App\System\Registry;

class DefaultController extends AbstractController
{
    public function index()
    {
        $url = Registry::get('config')['baseUrl'] . '/';
//        var_dump($_SESSION);
        $this->renderView('default/index', ['url' => $url]);
    }
}