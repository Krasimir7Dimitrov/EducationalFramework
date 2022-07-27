<?php
namespace App\Model\Collections;

class CarsCollection extends \App\System\BaseCollection
{
    protected $table = 'cars';

    public function getAllCars()
    {
        $query = "SELECT * FROM cars";

        return $this->db->fetchAll($query);
    }

    public function getCarById($id)
    {
        $query = "SELECT * FROM cars c  WHERE c.id = :id";

        return $this->db->fetchOne($query, compact('id'));
    }

    public function getCarsRegisteredBetween(string $startYear, string $endYear)
    {
        $query = "SELECT * FROM cars AS c WHERE c.first_registration BETWEEN :startYear AND :endYear ORDER BY c.first_registration";

        return $this->db->fetchAll($query, compact('startYear', 'endYear'));
    }

    public function getEmailByUserId(int $id)
    {
        $query = "SELECT email FROM users AS u WHERE u.id = :id";

        return $this->db->fetchOne($query, compact('id'));
    }
}