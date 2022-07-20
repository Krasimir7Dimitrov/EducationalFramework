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
        $controllerName = $this->config['routing']['defaultController'];
        if (!empty($uri_segments[1])) {
            $controllerName = "\\App\\Controllers\\".ucfirst(strtolower($uri_segments[1])).'Controller';
        }
        $this->setController($controllerName);

        //Get the action method
        $actionName = $this->config['routing']['defaultAction'];
        if (!empty($uri_segments[2])) {
            $actionName = strtolower($uri_segments[2]);
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
        $baseUrl = $this->config['baseUrl'];
        $controllerExists = class_exists($this->controller);
        if (!$controllerExists) {
            header("Location: http://localhost:8080/notfound/index"); die();
        }

        $controller = new $this->controller();
        $action = $this->action;
        $methodExists = method_exists($controller, $action);
        if (!$methodExists) {
            $reflect = new \ReflectionClass($controller);
            $ctrName = strtolower($reflect->getShortName());
            $ctrName = str_replace('controller', '', $ctrName);
            header("Location: {$baseUrl}/{$ctrName}/index"); die();
        }
        return $controller->$action();
    }

}