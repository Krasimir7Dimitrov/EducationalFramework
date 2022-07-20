<?php

namespace App\System;

class FrontController
{
    private $controller;

    private $action;

    private $params;

    private $config;


    public function __construct($options = [])
    {
        $this->config = Registry::get('config');

        if (empty($options)) {
            $this->parseUrl();
        }

        if (isset($options['controller'])) {
            $this->setController($options['controller']);
        }

        if (isset($options['action'])) {
            $this->setAction($options['action']);
        }

        if (isset($options['params'])) {
            $this->setParams($options['params']);
        }
    }

    private function parseUrl()
    {
        $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri_segments = explode('/', $uri_path);

        //Get the controller class
        if (!empty($uri_segments[1])) {
            $controllerName = "\\App\\Controllers\\".ucfirst(strtolower($uri_segments[1])).'Controller';
            $exists = class_exists($controllerName);
            if ($exists) {
                $this->setController($controllerName);
            }
        }

        //Get the action method
        $actionName = $this->config['routing']['defaultAction'];
        if (!empty($uri_segments[2])) {
            $actionName = strtolower($uri_segments[2]);
            $controllerInstance = new $this->controller();
            $exists = method_exists($controllerInstance, $actionName);

            if ($exists) {
                $this->action = $actionName;
            }
        }
        $this->setAction($actionName);
    }

    private function setController($controllerName)
    {
        $this->controller = $controllerName;
    }

    private function setAction($actionName)
    {
        $this->action = $actionName;
    }

    private function setParams($params)
    {

    }

    public function run()
    {
        $controllerExists = class_exists($this->controller);
        if (!$controllerExists) {
            $notFoundController = $this->config['routing']['NotFoundController'];

            header("Location: http://localhost:8080/notfound/index"); die;
            return (new $notFoundController())->index();
        }
        $controller = new $this->controller();

        $action = $this->action;
        $methodExists = method_exists($controller, $action);
        if (!$methodExists) {
            $defAction = 'index';
            return $controller->$defAction();
        }
        $controller->$action();
    }

}