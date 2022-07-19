<?php
namespace App\System\Interfaces;

interface DbAdaperInterface
{
    public function insert(array $data);

    public function update(array $data, $where);

    public function delete($id);
}