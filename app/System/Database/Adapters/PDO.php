<?php

namespace App\System\Database\Adapters;

use App\System\Database\Interfaces\DbAdapterInterface;

class PDO implements DbAdapterInterface
{

    private static $instance;

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


    public function fetchOne($sql, $data = [])
    {
        $stm = $this->connection->prepare($sql);
        foreach ($data as $key => $value) {
            $stm->bindParam(':'.$key, $data[$key]);
        }
        $stm->execute();

        return $stm->fetch(\PDO::FETCH_ASSOC);
    }


    public function fetchAll($sql, $data = [])
    {
        $stm = $this->connection->prepare($sql);
        foreach ($data as $key => $value) {
            $stm->bindParam(':'.$key, $data[$key]);
        }
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }




    /**
     * @return \App\System\PDO
     */
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

    /**
     * @param $data
     * @return false|int
     */
    public function insert($data)
    {
        if (empty($data)) {
            return false;
        }

        $arrayKeys = array_keys($data);
        $statement = 'INSERT INTO ' . $this->table . '(' . implode(", ", $arrayKeys) . ') VALUES (:'. implode(", :", $arrayKeys) .')';
        $query = $this->db->prepare($statement);

        foreach ($data as $key => $value) {
            $query->bindParam(':'.$key, $data[$key]);
        }

        $result = $query->execute();

        if ($result) {
            $rowCount = $query->rowCount();
            if ($rowCount) return (int) $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * @param $where
     * @param $data
     * @return false|int
     */
    public function update($where, $data)
    {
        if (empty($data)) {
            return false;
        }

        $set = $this->makeCondition($data);

        $whereArray = [];
        foreach ($where as $key => $value) {
            $whereArray[] = "$key = :w$key";
        }

        $addAnd = '';
        if (!empty($where)) {
            $addAnd = ' AND ';
        }

        $statement = 'UPDATE ' . $this->table . ' SET '. implode(', ', $set) .' WHERE 1'. $addAnd .implode(' AND ', $whereArray);
        $query = $this->db->prepare($statement);

        foreach ($data as $key => $value) {
            $query->bindParam(':'. $key, $data[$key]);
        }

        foreach ($where as $key => $value) {
            $query->bindParam(':w'. $key, $where[$key]);
        }

        return $query->rowCount();
    }

    /**
     * @param $where
     * @return false|int
     */
    public function delete($where)
    {
        $addAnd = '';
        if (empty($where)) {
            return false;
        } else {
            $addAnd = ' AND ';
        }

        $whereCondition = $this->makeCondition($where);

        $statement = 'DELETE FROM ' . $this->table . ' WHERE 1'. $addAnd . implode(' AND ', $whereCondition);
        $query = $this->db->prepare($statement);

        foreach ($where as $key => $value) {
            $query->bindParam(':'. $key, $where[$key]);
        }

        $query->execute();

        return $query->rowCount();
    }

    /**
     * @param array $condition
     * @return array
     */
    public function makeCondition(array $condition)
    {
        $conditionArray = [];
        foreach ($condition as $key => $value) {
            $conditionArray[] = "$key = :$key";
        }
        return $conditionArray;
    }






}