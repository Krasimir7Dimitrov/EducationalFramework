<?php
namespace App\System;

abstract class AbstractController
{
    public function __construct()
    {
        echo "Heere we are in abstractController constructor <hr/>";
    }

    abstract public function index();

    public function renderView($viewName, $params)
    {
        $viewDir = __DIR__ . '/../Views/';

        extract($params);
        require_once $viewDir.$viewName.'.phtml';
    }


    public function __destruct()
    {
        echo "Heere we are in abstractController destructor <hr/>";
    }
}