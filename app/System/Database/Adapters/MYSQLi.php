<?php

namespace App\System\Database\Adapters;

class MYSQLi implements \DbAdaperInterface
{
    private static $instance;
    public $connectionMySqli;

    private function __construct()
    {
        $this->connectionMySqli = $this->getMySqliConnection();
    }

    public static function getInstance(): MYSQLi
    {
        if (null === self::$instance) {
            self::$instance = new MYSQLi();
        }
        return self::$instance;
    }

    private function getMySqliConnection()
    {

    }

    public function insert(array $data)
    {
        // TODO: Implement update() method.
    }

    public function update(array $data, $where)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}