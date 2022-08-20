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
            $row = $this->usersCollection->getUserByUsername($username);

            if ($row['password'] !== sha1($password)) {
                $this->setFlashMessage('Your password is incorrect.');
                $this->redirect('auth', 'login');
            }
            if ($row['is_verified'] != 1) {
                $this->setFlashMessage('Your account is not activated. Please check your email for verification code.');
                $this->redirect('auth', 'login');
            }
            if (!empty($row) && $row['password'] === sha1($password)) {
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
            elseif (!empty($this->usersCollection->checkUsernameExist($regData['username']))) {
                $errors['username'] = 'This username already exists';
            }
            $data['username'] = $regData['username'];

            if (!filter_var($regData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'It is not valid email';
            }
            elseif (!empty($this->usersCollection->checkEmailExist($regData['email']))) {
                $errors['email'] = 'This email already exists';
            }
            $data['email'] = $regData['email'];

            if (empty($regData['password'])) {
                $errors['password'] = 'Password field is required';
            }
            elseif (strlen($regData['password']) < 8) {
                $errors['password'] = 'Your Password Must Contain At Least 8 Characters!';
            }
            elseif (strlen($regData['password']) > 30) {
                $errors['password'] = 'Your Password Must Contain not more than 30 Characters!';
            }
            elseif(!preg_match("#[0-9]+#",$regData['password'])) {
                $errors['password'] = "Your Password Must Contain At Least 1 Number!";
            }
            elseif(!preg_match("#[A-Z]+#",$regData['password'])) {
                $errors['password'] = "Your Password Must Contain At Least 1 Capital Letter!";
            }
            elseif(!preg_match("#[a-z]+#",$regData['password'])) {
                $errors['password'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
            }

            if ($regData['password'] !== $regData['repeat_password']) {
                $errors['repeatPass'] = "Password and Confirm Password must be same!";
            }

            if (empty($regData['terms'])) {
                $errors['terms'] = "To continue must confirm the terms!";
            }

            if (empty($errors)) {
                unset($regData['terms']);
                unset($regData['repeat_password']);
                $encryption = sha1($regData['password']);
                $regData['password'] = $encryption;
                $verificationCode = $regData['verification_code'] = $this->generateRandomString();

                $user = $this->usersCollection->create($regData);

                $link = "<div><a href='{$this->url}auth/verify?id={$user}&verification_code={$verificationCode}'>Click</a></div>";
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
        $code = $this->usersCollection->getVerificationCode($confirmReg['id']);
        if ($code['is_verified'] == 0 && $confirmReg['verification_code'] === $code['verification_code']) {
            $this->usersCollection->updateIsVerified($confirmReg['id']);
            $this->setFlashMessage('You verify your account successfully. You can sign in in your profile.');
            $this->redirect('auth', 'login');
        }
        if ($code['is_verified'] != 0) {
            $this->setFlashMessage('Your account is already activated.');
            $this->redirect('auth', 'login');
        }
    }

    public function reset()
    {
        $regData = $_POST;
        $errors = [];
        $data = [];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            if (!filter_var($regData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'It is not valid email';
            }
            $data['email'] = $regData['email'];
            if (!$this->usersCollection->checkEmailExist($regData['email'])) {
                $errors['email'] = 'We can not find user with that email address.';
            }
            if (empty($errors)) {
                $str = $this->generateRandomString(30);
                $this->usersCollection->deleteIfEmailExist($regData['email']);
                $data['email'] = $regData['email'];
                $data['token'] = $str;
                $this->usersCollection->createToken($data);

                $link = "<div><a href='{$this->url}auth/change?email={$regData['email']}&token={$str}'>Click</a></div>";
                $mail = new \App\System\Email\Email();
                $mail->to = $regData['email'];
                $mail->subject = 'This is your verification code';
                $mail->body = $link;
                $send = new \App\System\Email\NotificationEmail($mail);
                $send->send();

                $this->setFlashMessage('Please check email for reset password link.');
                $this->redirect('auth', 'login');
            }
        }

        $this->renderView('auth/reset', ['url' => $this->url, 'errors' => $errors, 'data' => $data]);
    }

    public function change()
    {
        $query = $_GET;
        $token = $this->usersCollection->getDataFromPassReset($query);
        if (empty($token)) {
            $this->redirect('auth', 'login');
        }

        $datetime = new DateTime();
        $now = $datetime->format('Y-m-d H:i:s');
        $time = strtotime($now) - strtotime($token['created_at']);
        if (($time / 3600) > 1) {
            $this->setFlashMessage('Your reset link is expired.');
            $this->redirect('auth', 'login');
        }
        $errors = [];
        $regData = $_POST;
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            if (empty($regData['password'])) {
                $errors['password'] = 'Password field is required';
            }
            elseif (strlen($regData['password']) < 8) {
                $errors['password'] = 'Your Password Must Contain At Least 8 Characters!';
            }
            elseif (strlen($regData['password']) > 30) {
                $errors['password'] = 'Your Password Must Contain not more than 30 Characters!';
            }
            elseif(!preg_match("#[0-9]+#",$regData['password'])) {
                $errors['password'] = "Your Password Must Contain At Least 1 Number!";
            }
            elseif(!preg_match("#[A-Z]+#",$regData['password'])) {
                $errors['password'] = "Your Password Must Contain At Least 1 Capital Letter!";
            }
            elseif(!preg_match("#[a-z]+#",$regData['password'])) {
                $errors['password'] = "Your Password Must Contain At Least 1 Lowercase Letter!";
            }

            if ($regData['password'] !== $regData['repeat_password']) {
                $errors['repeatPass'] = "Password and Confirm Password must be same!";
            }

            if (empty($errors)) {
                try {
                    $this->usersCollection->update(['password' => sha1($regData['password'])], ['email' => $query['email']]);
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
}