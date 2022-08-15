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

    public function getNumberOfCars($where)
    {
        $sql = "SELECT COUNT(c.id) AS cnt FROM cars AS c";
        $sql .= " INNER JOIN make AS mk ON mk.id = c.make_id";
        $sql .= " INNER JOIN models AS mo ON mo.id = c.model_id";
        $sql .= " WHERE 1 ";
        $data = [];
        foreach ($where as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
                $sql .= " AND c.{$key} = :{$key}";
            }
        }

        return $this->db->fetchOne($sql, $data)['cnt'];
    }

    public function updateCar($data, $where)
    {
        return $this->update($data, $where);
    }

    public function createCar($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function deleteCar($where)
    {
        return $this->db->delete($this->table, $where);
    }
    public function getSingleCar($id)
    {
        $data['id'] = $id;
        $sql = "SELECT c.id, mk.name AS make, mo.name AS model, c.first_registration, c.transmission, c.image, c.make_id, c.model_id";
        $sql .= " FROM $this->table AS c";
        $sql .= " INNER JOIN make AS mk ON mk.id = c.make_id INNER JOIN models AS mo ON mo.id = c.model_id";
        $sql .= " WHERE c.id = :id";
        return $this->db->fetchOne($sql, $data);
    }

    public function getRowsForAPageFromCars(int $numberOfRowsInAPage, int $rowsOffset, array $where = [], $order = '')
    {
        $sql = "SELECT c.id, mk.name AS make, mo.name AS model, c.first_registration, c.transmission, c.image";
        $sql .= " FROM $this->table AS c";
        $sql .= " INNER JOIN make AS mk ON mk.id = c.make_id INNER JOIN models AS mo ON mo.id = c.model_id";
        $sql .= " WHERE 1";
        $data = [];
        foreach ($where as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
                $sql .= " AND c.{$key} = :{$key}";
            }
        }
        $sql .= " ORDER BY $order";
        $sql .= " LIMIT $numberOfRowsInAPage OFFSET $rowsOffset";
        return $this->db->fetchAll($sql, $data);
    }

    public function getAllMakes()
    {
        $sql = "SELECT DISTINCT make FROM $this->table";
        return $this->db->fetchAll($sql);
    }

    public function getModels($make, $transmission)
    {
        $data = [
            'make' => $make,
            'transmission' => $transmission
        ];
        $sql = "SELECT DISTINCT model FROM $this->table WHERE make = ':make' AND transmission = ':transmission'";
        return $this->db->fetchAll($sql, $data);
    }
}