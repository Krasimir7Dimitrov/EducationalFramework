<?php
namespace App\System;

use App\System\Traits\Auth;

abstract class AbstractController
{
    use Auth;

    protected $config;
    public $flashMessages;

    public function __construct()
    {
        $this->config = Registry::get('config');
        $this->flashMessages = $_SESSION['flashMessages'];
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

}