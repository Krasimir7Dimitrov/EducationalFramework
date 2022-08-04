<?php
namespace App\Model\Collections;

class CarsCollection extends \App\System\BaseCollection
{
    protected $table = 'cars';

    public function getAllCars()
    {
        $sql = "SELECT * FROM cars";

        return $this->db->fetchAll($sql);
    }

    public function getCarById($id)
    {
        $sth = "SELECT * FROM cars c  WHERE c.id = :id";

        return $this->db->fetchOne($sth, ['id' => $id]);
    }

    public function getCarsRegisteredBetween(string $startYear, string $endYear)
    {
        $query = "SELECT * FROM cars AS c WHERE c.first_registration BETWEEN :startYear AND :endYear ORDER BY c.first_registration";

        return $this->db->fetchAll($query, compact('startYear', 'endYear'));
    }

    public function getCarsPagination($limit, $offset)
    {
        $sth = "SELECT * FROM cars c  LIMIT {$limit} OFFSET {$offset}";

        return $this->db->fetchAll($sth);
    }

    public function getCarsCount($where = [])
    {
        $sth = "SELECT count(id) AS count FROM cars";

        return $this->db->fetchOne($sth)['count'];
    }

    public function insertCar($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function getCarBySearchCriteria($where, $data)
    {
        $sql = "SELECT * FROM cars AS c";
        $sql .= " WHERE 1" . implode(' AND ', $where);

        return $this->db->fetchAll($sql, $data);
    }
}