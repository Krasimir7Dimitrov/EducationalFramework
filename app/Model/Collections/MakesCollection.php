<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class MakesCollection extends BaseCollection
{

    public function getAllMakes()
    {
        $sql = "SELECT * FROM make";

        return $this->db->fetchAll($sql);
    }

}