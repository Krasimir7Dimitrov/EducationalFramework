<?php

namespace App\System\Debugbar;

use App\System\Debugbar\Enums\DecorationTypes;

class Debugbar implements DebugBarDataInterface
{
    private $data;

    private $baseUrl;

    private $ip;

    private $userSession;

    private $controller;

    private $action;

    private $memoryUsed;

    private $httpMethod;

    private $queryString;

    private $requestData;

    private $executionTime;


    public function __construct(DebugDataInterface $debugData)
    {
        $this->setBaseUrl($debugData->getBaseUrl());
        $this->setIp($debugData->getIp());
        $this->setUserSession($debugData->getUserSession());
        $this->setController($debugData->getController());
        $this->setAction($debugData->getAction());
        $this->setMemoryUsed($debugData->getMemoryUsed());
        $this->setHttpMethod($debugData->getHttpMethod());
        $this->setQueryString($debugData->getQueryString());
        $this->setRequestData($debugData->getRequestData());
        $this->setExecutionTime($debugData->getExecutionTime());
        $this->uniteAllValuesInArray();
    }

    public function render(DecorationTypes $type)
    {
        $decorator = new \App\System\Debugbar\Decorator($this->getDebugData());
        $decorator->render($type);
    }

    public function getDebugData(): array
    {
        return $this->data;
    }

    private function setBaseUrl($baseUrl): void
    {
        $this->baseUrl = $baseUrl;
    }

    private function setIp($ip): void
    {
        $this->ip = $ip;
    }

    private function setUserSession($userSession): void
    {
        $this->userSession = $userSession;
    }

    private function setController($controller): void
    {
        $this->controller = $controller;
    }

    private function setAction($action): void
    {
        $this->action = $action;
    }

    private function setMemoryUsed($memoryUsed): void
    {
        $this->memoryUsed = $memoryUsed;
    }

    private function setHttpMethod($httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    private function setQueryString($queryString): void
    {
        $this->queryString = $queryString;
    }

    private function setRequestData($requestData): void
    {
        $this->requestData = $requestData;
    }

    private function setExecutionTime($executionTime): void
    {
        $this->executionTime = $executionTime;
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
