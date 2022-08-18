<?php

namespace App\System\Email;

use App\System\Registry;
use PHPMailer\PHPMailer\PHPMailer;

class NotificationEmail implements NotificationInterface
{

    /**
     * @var Email
     */
    private $email;

    private $emailConfig;

    public function __construct(Email $email)
    {
        $this->email = $email;
        $this->emailConfig = Registry::get('config')['emails'];
        if (empty($this->emailConfig)) {
            throw new \Exception('The config for the email is empty');
        }
    }

    public function send()
    : bool
    {

        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
//            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $this->emailConfig['connection']['smtp']['host'];
            $mail->Username   = $this->emailConfig['connection']['smtp']['user'];
            $mail->Password   = $this->emailConfig['connection']['smtp']['password'];
            $mail->Port       = $this->emailConfig['connection']['smtp']['port'];

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            //Recipients
            $mail->setFrom($this->emailConfig['from'], 'System');
            $mail->addAddress($this->email->to);                  //Add a recipient

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $this->email->subject;
            $mail->Body    = $this->email->body;
            //$mail->AltBody = $this->email->body;

            var_dump('hhhhhhhhhhhhhhhhhhhhhhhhh');
            return $mail->send();
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return false;
    }

    public function postQueue()
    {
        // TODO: Implement postQueue() method.
    }
}