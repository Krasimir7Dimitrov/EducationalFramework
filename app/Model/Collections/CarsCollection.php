<?php
namespace App\Model\Collections;

use \App\System\DB;

class CarsCollection extends \App\System\BaseCollection
{
    protected $table = 'cars';


    public function getAllCars()
    {
        $result = $this->db->query('SELECT * FROM cars');

        return $result->fetchAll();
    }

    public function getCarById($id)
    {
        $sth = $this->db->prepare(
            "SELECT * FROM cars c  WHERE c.id = :id");
        $sth->bindParam('id',$id);

        return $sth->fetchAll();
    }

}