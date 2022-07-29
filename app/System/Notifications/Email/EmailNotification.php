<?php

namespace App\System\Notifications\Email;

use App\Model\Collections\NotificationsCollection;
use App\System\Notifications\NotificationInterface;
use App\System\Registry;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

/**
 * Class EmailNotification
 * @package App\System\Notifications
 */
class EmailNotification implements NotificationInterface
{
    private Email $email;
    /**
     * @var array|mixed
     */
    private array $emailConfig;

    /**
     * @param Email $email
     * @throws \Exception
     */
    public function __construct(Email $email)
    {
        $this->email       = $email;
        $this->emailConfig = Registry::get('config')['emails'];
        if (empty($this->emailConfig)) {
            throw new \Exception('The config for the emails is empty!');
        }
    }

    public function send()
    : bool
    {

        //Create an instance; passing `true` enables exceptions
        //$mail = new PHPMailer(true);

        $startTime = Registry::get('startTime');
        try {
            $inst = EmailConnection::getInstance();
            var_dump((microtime(true) - $startTime), 'after get instance');
            $mail = $inst->connection;
            var_dump((microtime(true) - $startTime), 'after connection');

            //Recipients
            $mail->setFrom($this->emailConfig['from'], 'System');
            $mail->addAddress($this->email->to);                  //Add a recipient
            var_dump((microtime(true) - $startTime), 'after set address');

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->email->subject;
            $mail->Body    = $this->email->body;
            $mail->AltBody = $this->email->body;
            var_dump((microtime(true) - $startTime), 'after subject and body');

            return $mail->send();
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return false;
    }

    public function postToQueue()
    : bool
    {
        $serializedEmail = serialize($this->email);

        $notificationCollection = new NotificationsCollection();
        return $notificationCollection->createNotification($serializedEmail);
    }
}