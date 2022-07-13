<?php

class DB
{
    private static $conn = null;

    private function __construct()
    {
    }

    public static function getConn()
    {
        if (null === self::$conn) {
            try {
                $results = null;
                self::$conn = new \PDO("mysql:host=db;port=3306;charset=utf8mb4;dbname=db", 'user', 'password');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (\PDOException $e) {
                echo 'Connection Failed ' . $e->getMessage();
            }
        }
        return self::$conn;
    }
}