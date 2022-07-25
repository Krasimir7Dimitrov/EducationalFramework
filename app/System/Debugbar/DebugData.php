<?php

namespace App\System\Debugbar;

class DebugData implements DebugDataInterface
{

    private string $url;
    private string $ip;
    private array  $userSession;
    private string $controller;
    private string $controllerAction;
    private int    $memoryUsed;
    private string $httpMethod;
    private array  $requestData;
    private string $queryString;
    private float  $executionTime;

    /**
     * @param string $url
     * @param string $ip
     * @param array $userSession
     * @param string $controller
     * @param string $controllerAction
     * @param int $memoryUsed
     * @param string $httpMethod
     * @param array $requestData
     * @param string $queryString
     * @param float $executionTime
     */
    public function __construct(string $url, string $ip, array $userSession, string $controller, string $controllerAction, int $memoryUsed, string $httpMethod, array $requestData, string $queryString, float $executionTime)
    {
        $this->url              = $url;
        $this->ip               = $ip;
        $this->userSession      = $userSession;
        $this->controller       = $controller;
        $this->controllerAction = $controllerAction;
        $this->memoryUsed       = $memoryUsed;
        $this->httpMethod       = $httpMethod;
        $this->requestData      = $requestData;
        $this->queryString      = $queryString;
        $this->executionTime    = $executionTime;
    }


    public function getUrl()
    : string
    {
        return $this->url;
    }

    public function getIp()
    : string
    {
        return $this->ip;
    }

    public function getUserSession()
    : array
    {
        return $this->userSession;
    }

    public function getController()
    : string
    {
        return $this->controller;
    }

    public function getControllerAction()
    : string
    {
        return $this->controllerAction;
    }

    public function getMemoryUsed()
    : int
    {
        return $this->memoryUsed;
    }

    public function getHttpMethod()
    : string
    {
        return $this->httpMethod;
    }

    public function getRequestData()
    : array
    {
        return $this->requestData;
    }

    public function getQueryString()
    : string
    {
        return $this->queryString;
    }

    public function getExecutionTimeInMicroSeconds()
    : float
    {
        return $this->executionTime;
    }
}