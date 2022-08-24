<?php

namespace App\Model\Collections;

class UsersCollection extends \App\System\BaseCollection
{
    protected $table = 'users';

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = :username";

        return $this->db->fetchOne($sql, ['username' => $username]);
    }

    public function getUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";

        return $this->db->fetchOne($sql, ['email' => $email]);
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

}