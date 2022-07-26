<?php

namespace App\System\Debugbar;

use App\System\Registry;

class Debugbar implements DebugbarDataInterface
{
    private $data;

    private $url;

    private $controller;

    private $action;

    private $memoryUsed;

    private $executionTime;

    private $userInfo;

    private $ip;

    private $httpMethod;

    private $queryString;

    public function __construct(DebugDataInterface $debugData)
    {
        $this->setUrl($debugData->getUrl());
        $this->setController($debugData->getController());
        $this->setControllerAction($debugData->getControllerAction());
        $this->setIp($debugData->getIp());
        $this->setMemoryUsed($debugData->getMemoryUsage());
        $this->setExecutionTime($debugData->getExecutionTimeInMicroseconds());
        $this->setUserInfo($debugData->getUserSession());
        $this->setHttpMethod($debugData->getHttpMethod());
        $this->setQueryString($debugData->getQueryString());
        $this->getValuesOfAllProperties();
    }

    public function getDebugData(): array
    {
        return $this->data;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setControllerAction($controllerAction)
    {
        $this->action = $controllerAction;
    }

    public function setMemoryUsed($memoryUsage)
    {
        $this->memoryUsed = $memoryUsage;
    }

    public function setExecutionTime($executionTime)
    {
        $this->executionTime =$executionTime;
    }

    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    public function setUserInfo($userInfo)
    {
        $this->userInfo = $userInfo;
    }

    public function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function getValuesOfAllProperties()
    {
        $this->data = get_object_vars($this);
        unset($this->data['data']);
        $this->data['previousRequest']           = $_SESSION['DebugBar']['previousRequest'];
        $_SESSION['DebugBar']['previousRequest'] = array_filter($this->data, function ($key) {
                return $key !== 'previousRequest';
            }, ARRAY_FILTER_USE_KEY) ?? [];

        return $this->data;
    }

}