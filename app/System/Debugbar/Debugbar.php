<?php

namespace App\System\Debugbar;

class Debugbar
{
    private $data;

    private $baseUrl;

    private $ip;

    private $userSession;

    private $controller;

    private $action;

    private $memoryUsed;

    //private $executionTime;

    public $httpMethod = [];

    private $queryString;

    private $postData;

    private $postRequest;


    public function __construct()
    {
        $this->setBaseUrl();
        $this->setIp();
        $this->setUserSession();
        $this->setController();
        $this->setAction();
        $this->setMemoryUsed();
        $this->setHttpData();
        $this->setQueryString();
        $this->setPostData();
        $this->uniteAllValuesInArray();
        //$this->setExecutionTime();
    }

    /**
     * @return array
     */
    public function getDebugData(): array
    {
        return $this->data;
    }

    private function setBaseUrl():void
    {
        $baseUrl = ('URL: http://' . !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'N\A' . !empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : 'N\A';
        $this->baseUrl = $baseUrl;
    }

    private function setIp()
    {
        $this->ip = !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N\A';
    }

    private function setUserSession():void
    {
        $userSession = $_SESSION['user'];
        $this->userSession = $userSession;
    }

    private function setController()
    {
        $this->controller = __CLASS__;
    }

    private function setAction()
    {
        $this->action = __NAMESPACE__;
    }

    private function setMemoryUsed()
    {
        $this->memoryUsed = memory_get_usage();
    }

    private function setExecutionTime()
    {
        $startTime = Registry::get('startTime');
        $endTime = Registry::get('endTime');
        $this->executionTime = 'EXECUTION TIME: ' . ($endTime - $startTime);
    }

    private function setHttpData()
    {
        $postRequest = !empty($_POST) ? $_POST : 'N/A';

        $_SESSION['lastRequest'] = $_SESSION['currentRequest'] ?? null;
        $_SESSION['currentRequest'] = $this->postRequest;
    }

    public function getRequest()
    {
        return $_SESSION['lastRequest'] ?? null;
    }

    private function setQueryString()
    {
        $this->queryString = !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'N/A';
    }

    private function setPostData()
    {
        $this->postData = !empty($_POST) ? $_POST : 'N/A';
    }

    private function uniteAllValuesInArray()
    {
        $reflect = new \ReflectionClass($this);
        $props   = $reflect->getProperties();

        foreach ($props as $prop) {
            if ($prop->name !== 'data') {
                $this->data[$prop->name] = $this->{$prop->name};
            }
        }
    }
}
