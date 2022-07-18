<?php

namespace App\Model\Collections;

class DB3
{
    public $connection;
    private static $instance;

    const HOST          = "db";
    const PORT          = '3306';
    const DATABASE      = "db";
    const USER          = "user";
    const PASSWORD      = "password";

    private function __construct()
    {
        $this->connection = $this->getConnection();
    }

    public static function getInstance(): DB3
    {
        if (self::$instance === null) {
            self::$instance = new DB3();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        $mysqli = mysqli_connect(self::HOST,self::USER,self::PASSWORD,self::DATABASE, self::PORT);

        if (mysqli_connect_error()) {
            echo "Database Connection Error.";
            exit();
        }

        echo "Database Connection Successfully.";
        return $mysqli;
    }

}