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
        $dbAdapter = new DbAdapter();
        $this->db = $dbAdapter->getDefaultConnection();
    }
}