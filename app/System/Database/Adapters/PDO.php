<?php

namespace App\System\Database\Adapters;

class PDO implements \App\System\Interfaces\DbAdaperInterface
{
    /**
     * @var \App\System\PDO | null
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


    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new PDO();
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

    public function insert($table, array $data): int
    {
    if (empty($data)) {
        false;
    }

    $singleRecord = false;
    foreach ($data as $array) {
        if (is_array($array)) {
            $keys = array_keys($array);
            break;
        } else {
            $keys = array_keys($data);
            $singleRecord = true;
        }
    }

    if (empty($keys)) {
        throw new \Exception('There is not any values');
    }

    $insertQuery = "INSERT INTO $table (" . implode(', ', $keys) . ") VALUES";

    $allKeys = [];
    foreach ($data as $array) {
        if (is_array($array)) {
            $keys = array_keys($array);
            $allKeys[] = $keys;
        } else {
            $keys = array_keys($data);
            $allKeys[] = $keys;
            break;
        }
    }

    $allValues = [];
    $count = 0;
    foreach ($allKeys as $value) {
        $vals = array_values($value);
        if ($count === 0) {
            $allValues[] = "(:" . $count . implode(', :' . $count, $vals) . ")";
        } else {
            $allValues[] = ", (:" . $count . implode(', :' . $count, $vals) . ")";
        }
        $count++;
    }
    $valuesQuery = implode($allValues);

    $sth = $this->connection->prepare(
        $insertQuery . $valuesQuery
    );

    if ($singleRecord === true) {
        $key = array_keys($data);

        for ($x = 0; $x < count($data); $x++) {
            $sth->bindParam(':0' . $key[$x], $data[$key[$x]]);
        }
    } else {
        $count = 0;
        foreach ($data as $array) {
            $key = array_keys($array);

            for ($x = 0; $x < count($array); $x++) {
                $sth->bindParam(':' . $count . $key[$x], $array[$key[$x]]);
            }
            $count++;
        }
    }
    $sth->execute();

    return (int)$sth->rowCount();
}


    /**
     * @param array $data
     * @param array|null $where
     * @param int|null $id
     * @return void
     * @throws \Exception
     */
    public function update($table, array $data, $where): int
    {
        $query = "";
        if (!empty($where) and is_array($where)) {
            $whereVals = [];
            foreach($where as $key => $val) {
                $whereVals[] = "$key = :w$key";
                $query = implode(" AND " ,$whereVals);
            }
        } elseif (!empty($data) and is_integer($where)) {
            $query = "id = :id";
        } else {
            throw new \Exception('There is empty value or too many values');
        }

        $vals = [];
        foreach($data as $key => $val) {
            $vals[] = "$key = :$key";
        }

        $sql = "UPDATE " . $table . " c SET " . implode(", ", $vals) .  " WHERE 1 AND " . $query;
        $sth = $this->connection->prepare($sql);

        if (is_array($where)) {
            foreach ($where as $key => $value) {
                $sth->bindParam(':w' . $key, $where[$key]);
            }
        } else {
            $sth->bindParam(':id', $where);
        }
        foreach ($data as $key => $value) {
            $sth->bindParam(':' . $key, $data[$key]);
        }
        $sth->execute();

        return (int)$sth->rowCount();
    }

    public function delete($table, $id)
    {
        $sth = $this->connection->prepare(
            "DELETE FROM $table WHERE id = :id LIMIT 1"
        );
        $sth->bindParam(':id', $id);
        $sth->execute();

        return $sth->rowCount();
    }
}