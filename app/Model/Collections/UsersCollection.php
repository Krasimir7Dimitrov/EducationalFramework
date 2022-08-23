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

    public function getUserById($id)
    {
        $sql = "Select * from users where id = :id";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($data, $where)
    {
        return $this->db->update($this->table, $data, $where);
    }

    public function getVerificationCode($id)
    {
        $sql = "SELECT u.is_verified, u.verification_code FROM $this->table AS u WHERE u.id = :id ";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function updateIsVerified($id)
    {
        return $this->db->update($this->table, ['is_verified' => 1], ['id' => $id]);
    }

    public function checkUsernameExist($username)
    {
        $sql = "SELECT u.id, u.username FROM $this->table AS u WHERE u.username = :username";

        return $this->db->fetchOne($sql, ['username' => $username]);
    }

    public function checkEmailExist($email)
    {
        $sql = "SELECT u.id, u.email FROM $this->table AS u WHERE u.email = :email";

        return $this->db->fetchOne($sql, ['email' => $email]);
    }

    public function deleteIfEmailExist($email)
    {
        return $this->db->delete('password_resets', ['email' => $email]);
    }

    public function createToken($data)
    {
        return $this->db->insert('password_resets', $data);
    }

    public function getDataFromPassReset($where)
    {
        $sql = "SELECT * FROM password_resets AS pass WHERE 1";
        $data = [];
        foreach ($where as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
                $sql .= " AND pass.{$key} = :{$key}";
            }
        }
        return $this->db->fetchOne($sql, $data);
    }
}