<?php

namespace App\System;

class BaseCollection
{
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        $this->db = DB::getInstance()->connection;
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

    public function delete($where)
    {

    }

    public function makeCondition(array $condition)
    {
        $conditionArray = [];
        foreach ($condition as $key => $value) {
            $conditionArray[] = "$key = :$key";
        }
        return $conditionArray;
    }

    public function insertBatch()
    {
        //TO TRY AT HOME, not a must
    }
}