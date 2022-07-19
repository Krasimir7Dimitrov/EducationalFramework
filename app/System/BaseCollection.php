<?php

namespace App\System;

use App\System\Database\DbAdapter;

class BaseCollection
{
    /**
     * @var $db \App\System\Interfaces\DbAdaperInterface
     */
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        $dbAdapter = new DbAdapter();
        $this->db = $dbAdapter->getConnectionToDB();
    }
}