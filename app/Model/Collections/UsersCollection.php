<?php

namespace App\Model\Collections;

class UsersCollection extends \App\System\BaseCollection
{
    protected $table = 'users';

    public function getUserByUsername($username)
    {
        $sql = "Select * from users where username = :username";

        return $this->db->fetchOne($sql, ['username' => $username]);
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function getVerificationCode($id)
    {
        $sql = "SELECT u.is_verified, u.verification_code FROM $this->table AS u WHERE u.id = $id ";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function updateIsVerified($id)
    {
        return $this->db->update($this->table, ['is_verified' => 1], ['id' => $id]);
    }
}