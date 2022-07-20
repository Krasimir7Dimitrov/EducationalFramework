<?php
namespace App\System\Database\Interfaces;


interface DbAdapterInterface
{

    public function fetchOne($sql, $data);

    public function fetchAll($sql, $data);

    public function insert($data);

    public function update($table, $where, $data);

    public function delete($where);

    public static function closeConnection();

}