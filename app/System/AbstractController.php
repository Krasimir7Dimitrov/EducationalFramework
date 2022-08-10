<?php
namespace App\System;

use App\System\Debugbar\Debugbar;
use App\System\Traits\Auth;

abstract class AbstractController
{
    use Auth;

    protected $config;

    protected $flashMessages;

    public function __construct()
    {
        $this->config = Registry::get('config');
        $this->flashMessages = $_SESSION['flashMessages'];
    }

    public function getFlashMessages()
    {
        $flashMessages = $this->flashMessages ?? [];
        $this->consumeFlashMassages();

        return $flashMessages;
    }

    public function consumeFlashMassages()
    {
        $this->flashMessages = [];
        $_SESSION['flashMessages'] = [];
    }

    public function setFlashMessage($message)
    {
        $_SESSION['flashMessages'][] = $message;
        $this->flashMessages = $_SESSION['flashMessages'];
    }

    abstract public function index();

    public function renderView($viewName, $params)
    {
        $viewDir = __DIR__ . '/../Views/';

        extract($params);
        require_once $viewDir.$viewName.'.phtml';
    }

    protected function redirect($controller = '', $method = '', array $params = [])
    {
        $url = $this->config['baseUrl'];
        if (!empty($controller)) {
            $url.= '/'.$controller;
        }
        if (!empty($method)) {
            $url.= '/'.$method;
        }
        foreach ($params as $param) {
            $url .= '/' . $param;
        }

        header("Location: {$url}"); die;
    }

}