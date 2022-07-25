<?php

namespace App\System\Debugbar;

class Debugbar implements DebugBarDataInterface
{
    private $data;

    private $baseUrl;

    private $ip;

    private $userSession;

    private $controller;

    private $action;

    private $memoryUsed;


    public $httpMethod;

    private $queryString;

    private $requestData;

    private $executionTime;


    public function __construct(DebugDataInterface $debugData)
    {
        $this->setBaseUrl($debugData->getUrl());
        $this->setIp($debugData->getIp());
        $this->setUserSession($debugData->getUserSession());
        $this->setController($debugData->getController());
        $this->setAction($debugData->getControllerAction());
        $this->setMemoryUsed($debugData->getMemoryUsed());
        $this->setHttpMethod($debugData->getHttpMethod());
        $this->setQueryString($debugData->getQueryString());
        $this->setRequestData($debugData->getRequestData());
        $this->setExecutionTime($debugData->getExecutionTimeInMicroSeconds());
        $this->uniteAllValuesInArray();
    }

    /**
     * @return array
     */
    public function getDebugData(): array
    {
        return $this->data;
    }

    private function setBaseUrl($baseUrl):void
    {
        $this->baseUrl = $baseUrl;
    }

    private function setIp($ip)
    {
        $this->ip = $ip;
    }

    private function setUserSession($userSession):void
    {
        $this->userSession = $userSession;
    }

    private function setController($controller)
    {
        $this->controller = $controller;
    }

    private function setAction($controllerAction)
    {
        $this->action = $controllerAction;
    }

    private function setMemoryUsed($memoryUsage)
    {
        $this->memoryUsed = $memoryUsage;
    }

    private function setExecutionTime($executionTime)
    {
        $this->executionTime = $executionTime;
    }

    private function setHttpMethod($httpMethod)
    {
        $this->httpMethod = $httpMethod;
    }

    private function setQueryString($queryString)
    {
        $this->queryString = $queryString;
    }

    private function setRequestData($requestData)
    {
        $this->requestData = $requestData;
    }

    private function uniteAllValuesInArray()
    {
        $this->data = get_object_vars($this);
        unset($this->data['data']);
        $this->data['previousRequest']           = $_SESSION['DebugBar']['previousRequest'];
        $_SESSION['DebugBar']['previousRequest'] = array_filter($this->data, function ($key) {
                return $key !== 'previousRequest';
            }, ARRAY_FILTER_USE_KEY) ?? [];
    }
}
