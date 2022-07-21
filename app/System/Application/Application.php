<?php

namespace App\System\Application;
use App\System\Database\DbAdapter;
use App\System\Registry;

class Application
{
    /**
     * @var Application
     */
    private static $instance;

    private function __construct()
    {
        $this->startSession();
        try {
            $config = require __DIR__ . '/../../config/config.php';
            Registry::set('config', $config);

            $dbAdapter = new DbAdapter();
            Registry::set('dbAdapter', $dbAdapter);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public static function getInstance(): Application
    {
        if (is_null(self::$instance)) {
            self::$instance = new Application();
        }
        return self::$instance;
    }

    private function startSession()
    {
        return session_start();
    }

    public function run()
    {
        $frontCon = new \App\System\FrontController();
        $frontCon->run();
    }

}