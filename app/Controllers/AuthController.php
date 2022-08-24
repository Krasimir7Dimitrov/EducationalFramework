<?php

namespace App\Controllers;

use App\Model\Collections\UsersCollection;
use App\System\AbstractController;
use App\System\Registry;
use App\System\Traits\Auth;
use DateTime;
use mysql_xdevapi\Exception;

class AuthController extends AbstractController
{
    private $url;
    private UsersCollection $usersCollection;

    public function __construct()
    {
        parent::__construct();
        $this->url = Registry::get('config')['baseUrl'] . '/';
        $this->usersCollection = new UsersCollection();
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function login()
    {
        if ($this->isLoggedIn()) {
            $this->redirect();
        }

        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST'){
            $username = $_POST['username'] ?? '' ;
            $password = $_POST['password'] ?? '' ;
            $row = $this->usersCollection->getUser(['username' => $username]);


            if (!password_verify($password, $row['password'])) {
                $this->setFlashMessage('Your password is incorrect.');
                $this->redirect('auth', 'login');
            }
            if ($row['is_verified'] != 1) {
                $this->setFlashMessage("Your account is not activated. Please check your email for verification code. <br><form method='get'><span>Or</span><a href='{$this->url}auth/resend/{$row['id']}'> resend token</a></form>");
                $this->redirect('auth', 'login');
            }

            if (password_verify($password, $row['password'])) {
                unset($row['password']);
                $_SESSION['user'] = $row;
                $_SESSION['loggedIn'] = true;
                header("Location: {$this->config['baseUrl']}"); die();
            }
            $errors = [
                'authError' => 'incorrect login data',
            ];
        }
        $this->renderView('auth/login', ['errors' => $errors, 'url' => $this->url]);
    }

    public function resend()
    {
        $carsInst = new CarsController();
        $segment = $carsInst->urlSegments[3];
        if (empty($segment)) {
            $this->redirect('auth', 'login');
        }
        $row = $this->usersCollection->getUser(['id' => $segment]);
        $link = "<div><a href='{$this->url}auth/verify?verification_code={$row['verification_code']}'>Click</a></div>";
        $mail = new \App\System\Email\Email();
        $mail->to = $row['email'];
        $mail->subject = 'This is your verification code';
        $mail->body = $link;
        $send = new \App\System\Email\NotificationEmail($mail);
        $send->send();

        $this->setFlashMessage("We have sent verification code again");
        $this->redirect('auth', 'login');
    }

    /**
     * @throws \Exception
     */
    public function registration()
    {
        if ($this->isLoggedIn()) {
            $this->redirect();
        }
        $errors = [];
        $data = [];
        $regData = $_POST;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            if (empty($regData['username'])) {
                $errors['username'] = 'Username field is required';
            }
            elseif (strlen($regData['username']) < 4) {
                $errors['username'] = 'Your Username Must Contain At Least 3 Characters!';
            }
            elseif (strlen($regData['username']) > 20) {
                $errors['username'] = 'Your Username Must Contain not more than 20 Characters!';
            }
            elseif (!ctype_alpha($regData['username'])) {
                $errors['username'] = 'Username must contain only letters';
            }
            elseif (!empty($this->usersCollection->getUser(['username' => $regData['username']]))) {
                $errors['username'] = 'This username already exists';
            }
            $data['username'] = $regData['username'];

            if (empty($regData['email'])) {
                $errors['email'] = 'Email field is required';
            }
            elseif (!filter_var($regData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'It is not valid email';
            }
            elseif (!empty($this->usersCollection->getUser(['email' => $regData['email']]))) {
                $errors['email'] = 'This email already exists';
            }
            $data['email'] = $regData['email'];

            $passErr = $this->validatePassword($regData['password']);
            if (!empty($passErr)) {
                $errors['password'] = $passErr['password'];
            }

            if ($regData['password'] !== $regData['repeat_password']) {
                $errors['repeatPass'] = "Password and Confirm Password must be same!";
            }

            if (empty($regData['terms'])) {
                $errors['terms'] = "To continue must confirm the terms!";
            }
            if (empty($regData['g-recaptcha-response'])) {
                $errors['recaptcha'] = "To continue must set reCaptcha!";
            }

            if (empty($errors)) {
                if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
                {
                    $secret = Registry::get('config')['recaptcha']['secretKey'];
                    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
                    $responseData = json_decode($verifyResponse);
                    if(!$responseData->success) {
                        $this->setFlashMessage('Your have reCaptcha problem, please lease report to the site admin.');
                        $this->redirect('auth', 'login');
                    }
                }
                unset($regData['g-recaptcha-response']);
                unset($regData['terms']);
                unset($regData['repeat_password']);

                $encryption =  password_hash($regData['password'], PASSWORD_BCRYPT);
                $regData['password'] = $encryption;
                $addTokenToData = $regData['verification_code'] = bin2hex(random_bytes(16));
                $verificationCode = $addTokenToData;

                $user = $this->usersCollection->create($regData);
                if (empty($user)) {
                    $this->setFlashMessage('Your account has not been created.');
                    $this->redirect('auth', 'login');
                }
                $link = "<div><a href='{$this->url}auth/verify?verification_code={$verificationCode}'>Click</a></div>";
                $mail = new \App\System\Email\Email();
                $mail->to = $regData['email'];
                $mail->subject = 'This is your verification code';
                $mail->body = $link;
                $send = new \App\System\Email\NotificationEmail($mail);
                $send->send();

                $this->setFlashMessage('Your account has been created. Please check email for verification link.');
                $this->redirect('auth', 'login');
            }
        }

        $this->renderView('auth/registration', ['errors' => $errors, 'data' => $data, 'url' => $this->url]);
    }

    public function verify()
    {
        $confirmReg = $_GET;
        $userData = $this->usersCollection->getUser($confirmReg);
        if (empty($userData)) {
            $this->setFlashMessage('Your verification link is invalid or expired.');
            $this->redirect('auth', 'login');
        }
        if ($userData['is_verified'] != 0) {
            $this->setFlashMessage('Your account is already activated.');
            $this->redirect('auth', 'login');
        }
        $this->usersCollection->updateIsVerified($userData['id']);
        $this->setFlashMessage('You verify your account successfully. You can sign in in your profile.');
        $this->redirect('auth', 'login');
    }

    /**
     * @throws \Exception
     */
    public function reset()
    {
        if ($this->isLoggedIn()) {
            $this->redirect();
        }
        $regData = $_POST;
        $errors = [];
        $data = [];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            if (!filter_var($regData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'It is not valid email';
            }
            $userData = $this->usersCollection->getUser($regData);
            $data['user_id'] = $userData['id'];
            if (!$this->usersCollection->getUser(['email' => $regData['email']])) {
                //When we can not find user with that email address.
                $this->setFlashMessage('We have sent an reset password link, please check your email address.');
                $this->redirect('auth', 'login');
            }
            if (empty($errors)) {
                $token = bin2hex(random_bytes(16));
                $this->usersCollection->deleteIfUserIdExist($regData['user_id']);
                $data['token'] = $token;
                $this->usersCollection->createToken($data);

                $link = "<div><a href='{$this->url}auth/change?token={$token}'>Click</a></div>";
                $mail = new \App\System\Email\Email();
                $mail->to = $regData['email'];
                $mail->subject = 'This is your verification code';
                $mail->body = $link;
                $send = new \App\System\Email\NotificationEmail($mail);
                $send->send();

                $this->setFlashMessage('We have sent an reset password link, please check your email address.');
                $this->redirect('auth', 'login');
            }
        }

        $this->renderView('auth/reset', ['url' => $this->url, 'errors' => $errors, 'data' => $data]);
    }

    public function change()
    {
        if ($this->isLoggedIn()) {
            $this->redirect();
        }
        $query = $_GET;
        $resetPassData = $this->usersCollection->getDataFromPassReset($query);
        if (empty($resetPassData)) {
            $this->setFlashMessage('This token is invalid or expired.');
            $this->redirect('auth', 'login');
        }
        $datetime = new DateTime();
        $now = $datetime->format('Y-m-d H:i:s');
        $time = strtotime($now) - strtotime($resetPassData['created_at']);
        if (($time / 3600) > 1) {
            $this->setFlashMessage('Your reset link is invalid or expired.');
            $this->redirect('auth', 'login');
        }
        $errors = [];
        $regData = $_POST;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $passErr = $this->validatePassword($regData['password']);
            if (!empty($passErr)) {
                $errors['password'] = $passErr['password'];
            }
            if ($regData['password'] !== $regData['repeat_password']) {
                $errors['repeatPass'] = "Password and Confirm Password must be same!";
            }

            if (empty($errors)) {
                try {
                    $this->usersCollection->update(['password' => password_hash($regData['password'], PASSWORD_BCRYPT)], ['email' => $resetPassData['user_id']]);
                    $this->usersCollection->deleteIfUserIdExist($resetPassData['user_id']);
                } catch (\Throwable $e) {
                    $e->getMessage();
                    $this->setFlashMessage('Something went wrong.');
                    $this->redirect('auth', 'login');
                }
                $this->setFlashMessage('Your password was change successfully.');
                $this->redirect('auth', 'login');
            }
        }
        $this->renderView('auth/change', ['url' => $this->url, 'errors' => $errors]);
    }

    public function logout()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST'){
            unset($_SESSION['user']);
            unset($_SESSION['loggedIn']);
        }

        $redirectUrl = $this->config['baseUrl'].'/auth/login';
        header("Location: {$redirectUrl}"); die();
    }

    private function generateRandomString($length = 20) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    private function validatePassword($password): array
    {
        $errors = [];
        if (empty($password)) {
            $errors['password'] = 'Password field is required';
        }
        elseif (strlen($password) < 8) {
            $errors['password'] = 'Your Password Must Contain At Least 8 Characters!';
        }
        elseif (strlen($password) > 30) {
            $errors['password'] = 'Your Password Must Contain not more than 30 Characters!';
        }
        elseif(!preg_match("#[0-9]+#",$password)) {
            $errors['password'] = "Your Password Must Contain At Least 1 Number!";
        }
        elseif(!preg_match("#[A-Z]+#",$password)) {
            $errors['password'] = "Your Password Must Contain At Least 1 Capital Letter!";
        }
        elseif(!preg_match("#[a-z]+#",$password)) {
            $errors['password'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
        }

        return $errors;
    }
}