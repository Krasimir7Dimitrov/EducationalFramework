<?php

require_once 'DB.php';

class Listing
{

    /**
     * @var DB
     */
    private $db;

    public function __construct()
    {
        $this->db = DB::getConn();
    }

    public function getData(): object
    {
        $sql = "SELECT * FROM cars";
        return $this->db->query($sql);
    }
}