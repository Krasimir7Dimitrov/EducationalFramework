<?php
namespace App\Model\Collections;

use \App\System\PDO;

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

}