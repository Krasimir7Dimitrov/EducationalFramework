<?php

namespace App\System;

class DB
{
    /**
     * @var \App\System\DB | null
     */
    private static $instance;

    /**
     * @var \PDO
     */
    public $connection;

    const HOST          = "db";
    const PORT          = "3306";
    const DATABASE      = "db";
    const USER          = "user";
    const PASSWORD      = "password";

    /**
     * @throws \Exception
     */
    private function __construct()
    {
        $this->connection = $this->getConnection();
    }

    /**
     * @return \App\System\DB
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new DB();
        }

        return self::$instance;
    }

    /**
     * @return \PDO
     * @throws \Exception
     */
    private function getConnection()
    {
        try {
            $connection = new \PDO('mysql:host='. self::HOST . ';port=' .self::PORT. ';dbname='.self::DATABASE, self::USER, self::PASSWORD);
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\Throwable $e) {
            throw new \Exception('Can\'t establish mysql connection');
        }

        return $connection;
    }







}