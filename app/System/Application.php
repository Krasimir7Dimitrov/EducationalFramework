<?php

namespace App\System;

use App\System\Database\DbAdapter;
use App\System\Debugbar\DebugDataInterface;

class Application
{
    private static          $instance;
    private FrontController $frontController;

    private function __construct()
    {
        $this->startSession();
        // here we will initialize our Registry
        try {
            $config = require __DIR__.'/../config/config.php';
            Registry::set('config', $config);

            $dbAdapter = new DbAdapter();
            Registry::set('dbAdapter', $dbAdapter);

            $this->frontController = new \App\System\FrontController();

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

    private function startSession()
    {
        return session_start();
    }


    public static function getInstance()
    {
        if (is_null(self::$instance)) {
           self::$instance = new Application();
        }

        return self::$instance;
    }

    public function run()
    {
        return $this->frontController->run();
    }

    public function __destruct()
    {

    }

    /**
     * !!! make sure to run this after calling $this->run()
     * @return DebugDataInterface
     */
    public function getDebugData()
    : DebugDataInterface
    {
        $url               = $this->frontController->getUrl();
        $ip                = $this->frontController->getIp();
        $userSession       = $_SESSION['user'] ?? [];
        $controller        = $this->frontController->getController();
        $controllerAction  = $this->frontController->getControllerAction();
        $httpRequestMethod = $this->frontController->getHttpMethod();
        $requestData       = $this->frontController->getRequestData();
        $queryString       = $this->frontController->getQueryString();

        $memoryUsage              = memory_get_usage();
        $startTime                = Registry::get('startTime');
        $endTime                  = microtime(true);
        $applicationExecutionTime = $endTime - $startTime;

        return new \App\System\Debugbar\DebugData($url, $ip, $userSession, $controller, $controllerAction, $memoryUsage, $httpRequestMethod, $requestData, $queryString, $applicationExecutionTime);
    }
}