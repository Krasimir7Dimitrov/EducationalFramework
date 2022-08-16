<?php

namespace App\Model\Collections;

class CarsCollection extends \App\System\BaseCollection
{
    protected $table = 'cars';

    public function getAllCars()
    {
        $sql = "SELECT mk.name as makename, md.name as modelname, c.first_registration, c.transmission, c.make_id, c.model_id
                  FROM cars AS c
                  INNER JOIN make AS mk
                      ON mk.id=c.make_id
                  INNER JOIN models AS md
                      ON md.id=c.model_id";

        return $this->db->fetchAll($sql);
    }

    public function getCarById($id)
    {
        $sth = "SELECT c.id, md.name as modelname, mk.name as makename, c.first_registration, c.transmission, c.make_id, c.model_id
                  FROM cars AS c
                  INNER JOIN make AS mk
                      ON mk.id=c.make_id
                  INNER JOIN models AS md
                      ON md.id=c.model_id 
                  WHERE c.id = :id";

        return $this->db->fetchOne($sth, ['id' => $id]);
    }

    public function getCarsRegisteredBetween(string $startYear, string $endYear)
    {
        $query = "SELECT * FROM cars AS c WHERE c.first_registration BETWEEN :startYear AND :endYear ORDER BY c.first_registration";

        return $this->db->fetchAll($query, compact('startYear', 'endYear'));
    }

    public function getCarsPagination($limit, $offset)
    {
        $sth = "SELECT c.id, md.name as modelname, mk.name as makename, c.first_registration, c.transmission, c.created_at, c.updated_at
                  FROM cars AS c
                  INNER JOIN make AS mk
                      ON mk.id=c.make_id
                  INNER JOIN models AS md
                      ON md.id=c.model_id  
                  LIMIT {$limit} 
                  OFFSET {$offset}";

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

    public function getCarBySearchCriteria($where = [])
    {
        $sql = "SELECT c.id, md.name as modelname, mk.name as makename, c.first_registration, c.transmission
                FROM cars AS c
                INNER JOIN make AS mk
                  ON mk.id=c.make_id
                INNER JOIN models AS md
                  ON md.id=c.model_id";
        $sql .= " WHERE 1 " . implode(' AND ', $where);

        return $this->db->fetchAll($sql);
    }
}