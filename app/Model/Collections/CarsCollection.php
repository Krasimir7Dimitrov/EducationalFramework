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

}