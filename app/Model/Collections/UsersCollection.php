<?php

namespace App\Model\Collections;

class UsersCollection extends \App\System\BaseCollection
{
    protected $table = 'users';

    public function getUser(array $where)
    {
        $sql = "SELECT * FROM users AS u WHERE 1";
        $data = [];
        foreach ($where as $key => $value) {
            if (!empty($value)) {
                $data[$key] = $value;
                $sql .= " AND u.{$key} = :{$key}";
            }
        }

        return $this->db->fetchOne($sql, $data);
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($data, $where)
    {
        return $this->db->update($this->table, $data, $where);
    }

    public function updateIsVerified($id)
    {
        return $this->db->update($this->table, ['is_verified' => 1], ['id' => $id]);
    }

    public function deleteIfUserIdExist($user_id)
    {
        return $this->db->delete('password_resets', ['user_id' => $user_id]);
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