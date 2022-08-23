<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class UserTokensCollection extends BaseCollection
{
    protected $table = 'user_tokens';

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function getTokenRow($token)
    {
        $sql = "SELECT * FROM user_tokens WHERE token = :token ORDER BY id DESC";

        return $this->db->fetchOne($sql, ['token' => $token]);
    }
}