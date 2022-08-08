<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class ModelCollection extends BaseCollection
{
    protected $table = 'models';

    public function getAllModels()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->db->fetchAll($sql);
    }
}