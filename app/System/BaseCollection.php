<?php

namespace App\System;

use App\System\Database\DbAdapter;
use App\System\Interfaces\DbAdaperInterface;

class BaseCollection
{
    /**
     * @var $db DbAdaperInterface
     */
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        /** @var $dbAdapter DbAdapter */
        $dbAdapter = Registry::get('dbAdapter');
        $this->db = $dbAdapter->getConnectionToDB();
    }

    public function update($data, $where)
    {
        $this->db->update($this->table, $data, $where);
    }
}