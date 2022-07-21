<?php
namespace App\System\Interfaces;

interface DbAdaperInterface
{
    public function insert($table, array $data);

    public function update($table, array $data, $where);

    public function delete($table, $id);
}