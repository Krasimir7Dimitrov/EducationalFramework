<?php

namespace App\System;

use App\Library\Debugbar\DebugData;
use App\Library\Debugbar\Interfaces\DebugDataInterface;
use App\System\Database\DbAdapter;

class Application
{
    private static $instance;
    private FrontController $frontController;

    /**
     * @throws \Exception
     */
    private function __construct()
    {
        $this->startSession();
        // here we will initialize our Registry
        try {
            $config = require __DIR__.'/../config/config.php';
            Registry::set('config', $config);

            $dbAdapter = new DbAdapter();
            Registry::set('dbAdapter', $dbAdapter);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        $this->frontController = new FrontController();
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

    /**
     * @throws \Exception
     */
    public function __destruct()
    {

    }

    public function getDebugData(): DebugDataInterface
    {
        $url              = $this->frontController->getUrl();
        $ip               = $this->frontController->getIp();
        $controller       = $this->frontController->getController();
        $controllerAction = $this->frontController->getControllerAction();
        $httpMethod       = $this->frontController->getHttpMethod();
        $requestData      = $this->frontController->getRequestData();
        $queryString      = $this->frontController->getQueryString();

        $userSession              = $_SESSION['user'] ?? [];
        $memoryUsage              = $this->frontController->getMemoryUsage();
        $startTime                = Registry::get('startTime');
        $endTime                  = microtime(true);
        $applicationExecutionTime = $endTime - $startTime;

        return new DebugData($url, $ip, $userSession, $controller, $controllerAction, $memoryUsage, $httpMethod, $requestData, $queryString, $applicationExecutionTime);
    }

}