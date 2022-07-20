<?php
namespace App\Model\Collections;

class CarsCollection extends \App\System\BaseCollection
{
    public function __construct()
    {
        parent::__construct();

        echo "Heere we are in cars collection constructor <hr/>";
    }


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

    public function __destruct()
    {
        parent::__destruct();

        echo "Heere we are in cars collection destructor <hr/>";
    }

}