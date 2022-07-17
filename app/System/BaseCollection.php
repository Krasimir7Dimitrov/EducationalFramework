<?php

namespace App\System;

use Cassandra\Date;

class BaseCollection
{
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        $this->db = DB::getInstance()->connection;
    }


    /**
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function insert(array $data): int
    {
        if (empty($data)) {
            throw new \Exception('There is not any values');
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

        $insertQuery = "INSERT INTO $this->table (" . implode(', ', $keys) . ") VALUES";

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

        $sth = $this->db->prepare(
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
    public function update(array $data, array $where = null, int $id = null)
    {
        $query = "";
        if (!empty($where) and empty($id)) {
            $whereVals = [];
            foreach($where as $key => $val) {
                $whereVals[] = "$key = :w$key";
                $query = implode(" AND " ,$whereVals);
            }
        } elseif (!empty($id) and empty($where)) {
            $query = "id = :id";
        } else {
            throw new \Exception('There is empty value or too many values');
        }

        $vals = [];
        foreach($data as $key => $val) {
            $vals[] = "$key = :$key";
        }

        $sql = "UPDATE $this->table c SET " . implode(", ", $vals) .  " WHERE 1 AND " . $query;

        $sth = $this->db->prepare($sql);

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $sth->bindParam(':w' . $key, $where[$key]);
            }
        } else {
            $sth->bindParam(':id', $id);
        }
        foreach ($data as $key => $value) {
            $sth->bindParam(':' . $key, $data[$key]);
        }
        $sth->execute();

        return (int)$sth->rowCount();
    }

    public function delete($id)
    {
        $sth = $this->db->prepare(
            "DELETE FROM $this->table WHERE id = :id LIMIT 1"
        );
        $sth->bindParam(':id', $id);
        $sth->execute();

        return $sth->rowCount();
    }
}