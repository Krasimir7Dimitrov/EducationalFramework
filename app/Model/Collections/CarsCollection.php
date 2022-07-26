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
}