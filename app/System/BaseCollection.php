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
        $this->db = $dbAdapter->getDefaultConnection();
    }

    public function update($where, $data)
    {
        return $this->db->update($this->table, $where, $data);
    }
}