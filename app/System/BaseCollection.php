<?php

namespace App\System;

use App\System\Database\DbAdapter;

class BaseCollection
{
    /** @var $db \App\System\Database\Interfaces\DbAdapterInterface */
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        /** @var  $dbAdapter \App\System\Database\DbAdapter */
        $dbAdapter = Registry::get('dbAdapter');
        $this->db = $dbAdapter;

        echo "Heere we are in base collection constructor <hr/>";
    }

    public function update($where, $data)
    {
        return $this->db->update($this->table, $where, $data);
    }

    public function __destruct()
    {
        echo "Heere we are in base collection destructor <hr/>";
    }
}