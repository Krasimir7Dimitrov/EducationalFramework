<?php

namespace App\System\Database\Adapters;

use App\System\Database\Interfaces\DbAdapterInterface;

class MYSQLI implements DbAdapterInterface
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

    public static function getInstance(): MYSQLI
    {
        if (self::$instance === null) {
            self::$instance = new MYSQLI();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        $mysqli = new \mysqli(self::HOST,self::USER,self::PASSWORD,self::DATABASE, self::PORT);

        if (mysqli_connect_error()) {
            echo "Database Connection Error.";
            exit();
        }

        return $mysqli;
    }

    public function fetchOne($sql, $data = [])
    {

    }
    public function fetchAll($sql, $data = [])
    {
        $sql = "SELECT * FROM cars"; // SQL with parameters

        $stmt = $this->connection->prepare($sql);

        $stmt->execute();
        $result = $stmt->get_result(); // get the mysqli result

       // var_dump($result);

        $mem = memory_get_usage();
//        $allResults = $result->fetch_all(MYSQLI_ASSOC);
//        $arr = [];
//
//        foreach ($allResults as $item) {
//            $arr[] = $item['model'].' - '.$item['transmission'];
//        }
        $arr = [];
        while($row = $result->fetch_assoc()) {
            $arr[] = $row['model'].' - '.$row['transmission'];
        }
        $mem1 =  memory_get_usage();

        var_dump($mem, $mem1, $arr);

        die;

        return $result->fetch_assoc(); // fetch data
    }

    public function insert($data)
    {

    }

    public function update($where, $data)
    {

    }

    public function delete($where)
    {

    }

    public static function closeConnection()
    {
        self::$instance = null;
    }

}