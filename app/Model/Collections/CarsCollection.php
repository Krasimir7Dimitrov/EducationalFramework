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

    public function getNumberOfAllCars()
    {
        $sql = "SELECT COUNT(id) cnt FROM $this->table";
        return $this->db->fetchOne($sql)['cnt'];
    }

    public function getRowsForAPageFromCars(int $numberOfRowsInAPage, int $rowsOffset)
    {
        $sql = "SELECT * FROM $this->table LIMIT $numberOfRowsInAPage OFFSET $rowsOffset";
        return $this->db->fetchAll($sql);
    }

}