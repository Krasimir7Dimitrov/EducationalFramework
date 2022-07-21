<?php

namespace App\System;

use App\System\Database\DbAdapter;

class Application
{
    private static $instance;

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
        return (new \App\System\FrontController())->run();
    }

    public function __destruct()
    {
//        var_dump($_SERVER);
//        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//        var_dump($actual_link);
//        //var_dump($_SESSION['user']);
//        var_dump(memory_get_usage());
//        var_dump($_SERVER['HTTP_X_FORWARDED_FOR']);
    }
}