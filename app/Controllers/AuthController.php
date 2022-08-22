<?php

namespace App\Controllers;

use App\Model\Collections\UsersCollection;
use App\System\AbstractController;
use App\System\Notifications\Email\EmailNotification;
use App\System\Registry;
use App\System\Traits\Auth;

class AuthController extends AbstractController
{
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
            $usersCollection = new UsersCollection();
            $row = $usersCollection->getUserByUsername($username);
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
        $this->renderView('auth/login', ['errors' => $errors]);
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

    public function forgotPassword()
    {
        $userEmail = $_POST['email'] ?? null;
        $userCollection = new UsersCollection();
        $user = $userCollection->getUserByEmail($userEmail);

        $emailHtml = '<div><p>Please click on this link to reset your password <a href="">Click here</a></p></div>';

        $email = new \App\System\Notifications\Email\Email();
        $email->to = 'fake@mail.vc';
        $email->subject = 'Reset password';
        $email->body = var_export('<div><p>Please click on this link to reset your password <a href="http://localhost:8080/auth/resetPassword">Click here</a></p></div>', true);

        if ($user) {
            $emailNotification = new EmailNotification($email);

            if ($emailNotification->send()) {
                $message = 'Email was sent to email: ' . $userEmail . '.Please check you email.';
                $this->setFlashMessage($message);
            }
        }

        $this->renderView('auth/forgotPassword', []);
    }

    public function resetPassword()
    {

        $this->renderView('auth/reset', []);
    }

}