<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class MakeCollection extends BaseCollection
{
    protected $table = 'make';

    public function getAllMakes()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->db->fetchAll($sql);
    }
}