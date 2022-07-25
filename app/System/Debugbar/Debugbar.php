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
        $this->setRequestData($debugData->getRequestData());
    }

    public function getDebugData()
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

    public function setRequestData($requestData)
    {
//        $this->lastPostData = !empty($_POST) ? $_POST : 'N/A';
//
//        $_SESSION['lastRequest'] = $_SESSION['currentRequest'] ?? null;
//        $_SESSION['currentRequest'] = $this->lastPostData;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getValuesOfAllProperties(): array
    {
        $data = [];
        $values = get_object_vars($this);

        foreach ($values as $key => $value) {
            $data[$key] = $value;
        }

//        $data['lastPostData'] = json_encode($this->getLastPostData());
//        $data['lastGetData'] = json_encode($this->getLastGetData());

        try {
            Registry::set('debugBarProps', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $data;
    }

}