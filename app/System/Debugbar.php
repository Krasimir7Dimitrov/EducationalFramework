<?php

namespace App\System;

class Debugbar
{
    public $url;

    public $controller;

    public $action;

    public $memoryUsed;

    public $executionTime;

    public $userInfo;

    public $ip;

    public $httpMethod;

    public $queryString;

    public $postData;

    public $lastPostData;

    public $lastGetData;

    public function __construct()
    {
        $this->setUrl();
        $this->setController();
        $this->setAction();
        $this->setIp();
        $this->setMemoryUsed();
        $this->setExecutionTime();
        $this->setUserInfo();
        $this->setHttpMethod();
        $this->setQueryString();
        $this->setPostData();
    }

    public function setUrl()
    {
        $this->url = 'URL: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    public function setController()
    {
        $this->controller = 'CONTROLLER: ' . __CLASS__;
    }

    public function setAction()
    {
        $this->action = " ACTION: ";
    }

    public function setMemoryUsed()
    {
        $this->memoryUsed = 'MEMORY USAGE: '. memory_get_usage();
    }

    public function setExecutionTime()
    {
        $startTime = Registry::get('startTime');
        $endTime = Registry::get('endTime');
        $this->executionTime = 'EXECUTION TIME: ' . ($endTime - $startTime);
    }

    public function setIp()
    {
        $this->ip = 'IP: ' . !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'N\A';
    }

    public function setUserInfo()
    {
        $this->userInfo = 'USER: ' . !empty($_SESSION['user']) ? $_SESSION['user']['username'] : 'N\A';
    }

    public function setHttpMethod()
    {
        $this->httpMethod = 'HTTP METHOD: ' . !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'N/A';
    }

    public function setQueryString()
    {
        $this->queryString = 'QUERY STRING: ' . !empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : 'N/A';
    }

    public function setPostData()
    {
        $this->lastPostData = !empty($_POST) ? $_POST : 'N/A';

        $_SESSION['lastRequest'] = $_SESSION['currentRequest'] ?? null;
        $_SESSION['currentRequest'] = $this->lastPostData;
    }

    public function getLastPostData()
    {
        return $_SESSION['lastRequest'] ?? null;
    }

    public function setGetRequestData()
    {
        $this->lastGetData = !empty($_GET) ? $_GET : 'N/A';

        $_SESSION['lastGetRequest'] = $_SESSION['currentGetRequest'] ?? null;
        $_SESSION['currentGetRequest'] = $this->lastGetData;
    }

    public function getLastGetData()
    {
        return $_SESSION['lastGetRequest'] ?? null;
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

        $data['lastPostData'] = json_encode($this->getLastPostData());
        $data['lastGetData'] = json_encode($this->getLastGetData());

        try {
            Registry::set('debugBarProps', $data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        return $data;
    }

}