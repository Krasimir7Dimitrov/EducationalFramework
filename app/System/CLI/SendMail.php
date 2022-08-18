<?php

namespace App\System\CLI;
//Load Composer's autoloader

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class SendMail
{
    public function sendMAil(string $email, string $html): void
    {
        $mail = new PHPMailer(true);

        try
        {
            //Connection
            $mail->IsSMTP();
            $mail->Mailer = "smtp";
            $mail->SMTPDebug  = 1;
            $mail->SMTPAuth   = TRUE;
            $mail->SMTPSecure = "ssl";
            $mail->Port       = 465;
            $mail->Host       = "smtp.abv.bg";
            $mail->Username   = "krasibalboa@abv.bg";
            $mail->Password   ='';

            //Address
            $mail->IsHTML(true);
            $mail->AddAddress($email, "Krasimir Dimitrov");
            $mail->SetFrom("krasibalboa@abv.bg", "Krasimir Dimitrov");
            $mail->AddCC($email, "Krasimir Dimitrov");
            $mail->Subject = "Incredible offer";
            $content = $html;

            //Assertion
            $mail->MsgHTML($content);
            if(!$mail->Send()) {
                echo "Error while sending Email.";
            } else {
                echo "Email sent successfully";
            }
        }

        catch
        (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}