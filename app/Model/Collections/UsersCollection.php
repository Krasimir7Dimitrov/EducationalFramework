<?php

namespace App\Model\Collections;

class UsersCollection extends \App\System\BaseCollection
{

    public function getUserByUsername($username)
    {
        $sql = "Select * from users where username = :username";

        return $this->db->fetchOne($sql, ['username' => $username]);
    }

}