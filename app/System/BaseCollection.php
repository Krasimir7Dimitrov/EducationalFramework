<?php

namespace App\System;

class BaseCollection
{
    protected $db;
    protected $table = 'none';

    public function __construct()
    {
        $this->db = DB::getInstance()->connection;
    }

    public function insert($data)
    {
        //виждате как е чистия SQL, уверявате се че заявката работи в хейди в чист вид, и се опитвате да го адаптирате и да ескейпнете променливите
    }

    public function update($where, $data)
    {

    }

    public function delete($where, $data)
    {

    }
}