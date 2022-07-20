<?php

namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;

class NotfoundController extends AbstractController
{

    public function index()
    {
        $this->renderView('404/404', []);
    }
}