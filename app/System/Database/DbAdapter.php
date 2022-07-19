<?php
namespace App\System\Database;


class DbAdapter
{
    public $config;
    private $connectionToDB;

    public function __construct()
    {
        $config = require __DIR__."/../../config/config.php";
        $this->config = $config;
        var_dump($config);
        $connectionType = empty($config['db']['dbAdapter']) ? 'PDO' : $config['db']['dbAdapter'];
        var_dump($connectionType);
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