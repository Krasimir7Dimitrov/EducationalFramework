<?php
namespace App\System\Database;


use App\System\Registry;

class DbAdapter
{
    public $config;
    private $connectionToDB;

    public function __construct()
    {
        $config = Registry::get('config');
        $this->config = $config;
        $connectionType = empty($config['db']['dbAdapter']) ? 'PDO' : $config['db']['dbAdapter'];
        $this->setConnectionToDb($connectionType);
    }


    private function connectionFactory($connectionType)
    {
        switch ($connectionType) {
            case 'PDO':
                $connection = \App\System\Database\Adapters\PDO::getInstance();
                break;
            case 'MYSQLI':
                $connection = \App\System\Database\Adapters\MYSQLi::getInstance();
                break;
            default:
                $connection = \App\System\Database\Adapters\PDO::getInstance();
        }
        return $connection;
    }

    private function setConnectionToDb($connectionType): void
    {
        $this->connectionToDB = $this->connectionFactory($connectionType);
    }

    /**
     * @return mixed
     */
    public function getConnectionToDB()
    {
        return $this->connectionToDB;
    }
}