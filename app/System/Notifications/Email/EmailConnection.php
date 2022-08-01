<?php

namespace App\System\Notifications\Email;
use App\System\Registry;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailConnection
{
    private static $instance;

    private array $emailConfig;

    public $connection;

    private PHPMailer $mail;

    /**
     * @throws \Exception
     */
    private function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->emailConfig = Registry::get('config')['emails'];
        $this->connection = $this->getConnection();
    }

    public static function getInstance(): EmailConnection
    {
        //return new EmailConnection();
        if (is_null(self::$instance)) {
            self::$instance = new EmailConnection();
        }

        return self::$instance;
    }


    private function getConnection()
    {

        $var = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];

        $this->mail->isSMTP();
        $this->mail->Host       = $this->emailConfig['connection']['smtp']['host'];
        $this->mail->Username   = $this->emailConfig['connection']['smtp']['user'];
        $this->mail->Password   = $this->emailConfig['connection']['smtp']['password'];
        $this->mail->Port       = $this->emailConfig['connection']['smtp']['port'];
        $this->mail->SMTPKeepAlive = true;
        $this->mail->SMTPOptions = $var;

        return $this->mail;
    }
}