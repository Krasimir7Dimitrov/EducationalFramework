<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class ModelsCollection extends BaseCollection
{

    public function getAllModels()
    {
        $sql = "SELECT * FROM models";

        return $this->db->fetchAll($sql);
    }

}