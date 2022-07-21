<?php
namespace App\System;

use App\System\Debugbar\AbstractDebugger;
use App\System\Traits\Auth;

abstract class AbstractController
{
    use Auth;

    protected $config;

    public function __construct()
    {
        $test = new AbstractDebugger();
        $hi = $test->getDebugData();
        var_dump($hi) ;die();
        $this->config = Registry::get('config');
    }

    abstract public function index();

    public function renderView($viewName, $params)
    {
        $viewDir = __DIR__ . '/../Views/';

        extract($params);
        require_once $viewDir.$viewName.'.phtml';
    }

    protected function redirect($controller = '', $method = '')
    {
        $url = $this->config['baseUrl'];
        if (!empty($controller)) {
            $url.= '/'.$controller;
        }
        if (!empty($method)) {
            $url.= '/'.$method;
        }

        header("Location: {$url}"); die;
    }

}