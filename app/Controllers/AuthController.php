<?php

namespace App\Controllers;

use App\Model\Collections\UsersCollection;
use App\System\AbstractController;
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
                'authError' => 'incorect login data',
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

}