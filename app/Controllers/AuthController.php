<?php

namespace App\Controllers;

use App\Model\Collections\UsersCollection;
use App\Model\Collections\UserTokensCollection;
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
            $username        = $_POST['username'] ?? '' ;
            $password        = $_POST['password'] ?? '' ;
            $usersCollection = new UsersCollection();
            $row             = $usersCollection->getUserByUsername($username);
            if (!empty($row) && password_verify($password, $row['password'])) {
                unset($row['password']);
                $_SESSION['user']     = $row;
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
        $userEmail      = $_POST['email'] ?? null;
        $userCollection = new UsersCollection();
        $user           = $userCollection->getUserByEmail($userEmail);

        $emailResetPasswordLink = "http://localhost:8080/auth/resetPassword?user_id=" . $user['id'] . "&token=" . $this->getToken($user);

        $email          = new \App\System\Notifications\Email\Email();
        $email->to      = 'fake@mail.vc';
        $email->subject = 'Reset password';
        $email->body    = var_export('<div><p>Please click on this link to reset your password <a href="' . $emailResetPasswordLink . '">Click here</a></p></div>', true);

        if (isset($_POST['submit'])) {
            $emailNotification = new EmailNotification($email);
            $emailNotification->send();

            $message = 'Email was sent to email: ' . $userEmail . '.Please check you email.';
            $this->setFlashMessage($message);
        }

        $this->renderView('auth/forgotPassword');
    }

    public function resetPassword()
    {
        $userToken = $_GET['token'];
        $userId    = $_GET['user_id'];
        $method    = $_SERVER['REQUEST_METHOD'];

        if ($method == 'GET') {

            $usersTokensCollection = new UserTokensCollection();
            $tokenRow              = $usersTokensCollection->getTokenRow($userToken);

            if (!empty($tokenRow) && $userToken !== $tokenRow['token']) {
                $message = 'Your token is invalid or expired';
                $this->setFlashMessage($message);
                $this->redirect('auth', 'login');
            }

            if (!empty($tokenRow) && strtotime($tokenRow['created_at']) < time() - (60 * 60)) {
                $message = 'Your token is invalid or expired';
                $this->setFlashMessage($message);
                $this->redirect('auth', 'login');
            }
        }

        if ($method == 'POST') {
            $newPassword    = $_POST['new_password'];
            $repeatPassword = $_POST['repeat_password'];

            if (strcmp($newPassword, $repeatPassword) === 0) {
                $usersCollection = new UsersCollection();
                $userToBeUpdated = $usersCollection->getUserById($userId);

                $where = [
                    'id' => $userToBeUpdated['id']
                ];

                $usersCollection->update($where, ['password' => password_hash($newPassword, PASSWORD_BCRYPT)]);

                $message = 'Successfully reset password. You can now login with your new password';
                $this->setFlashMessage($message);
                $this->redirect('auth', 'login');
            }
        }

        $this->renderView('auth/reset');
    }

    public function getToken($user)
    {
        $token = substr(md5(rand()), 0, 50);

        $userTokenRecord = [
            'user_id' => $user['id'],
            'token'   => $token
        ];

        $usersTokenCollection = (new UserTokensCollection())->create($userTokenRecord);

        return $token;
    }

    public function register()
    {
        $method    = $_SERVER['REQUEST_METHOD'];

        if ($method == 'POST') {
            $usersCollection = new UsersCollection();
            $validatedResults = $this->validatePostFields($_POST);
            if (empty($validatedResults['errors'])) {
                $newUser = [
                  'username'   => $_POST['username'],
                  'first_name' => $_POST['first_name'],
                  'last_name'  => $_POST['last_name'],
                  'email'      => $_POST['email'],
                  'password'   => password_hash($_POST['password'], PASSWORD_BCRYPT),
                ];

                $result = $usersCollection->create($newUser);

                if (!empty($result)) {
                    $message = 'Successfully created user. You can now login';
                    $this->setFlashMessage($message);
                    $this->redirect('auth', 'login');
                }

            }
        }

        $this->renderView('auth/register', ['validatedResults' => $validatedResults]);
    }

    public function validatePostFields($post)
    {
        $firstName  = $post['first_name'];
        $lastName   = $post['last_name'];
        $username   = $post['username'];
        $email      = $post['email'];
        $password   = $post['password'];
        $repeatPass = $post['repeat_password'];

        $errors = $class = [];
        $class['first_name'] = $class['last_name'] =
        $class['username'] = $class['email'] =
        $class['password'] = $class['repeat_password'] ='class="form-control is-valid"';

        $usersCollection = new UsersCollection();
        $checkEmailExist = $usersCollection->getUserByEmail($email);
        $checkUsernameExists = $usersCollection->getUserByUsername($username);

        if (!empty($checkEmailExist)) {
            $errors['email'] = 'This email already exists!';
            $class['email'] = 'class="form-control is-invalid"';
        } else {
            if (is_string($email) && strlen($email) > 50) {
                $errors['email'] = 'Email should not be more than 50 characters';
                $class['email']  = 'class="form-control is-invalid"';
            }
        }

        if (is_string($username) && $username !== '' && strlen($username) > 50) {
            $errors['username'] = 'Username should not be more than 50 characters';
            $class['username']  = 'class="form-control is-invalid"';
        }

        if (!empty($checkUsernameExists)) {
            $errors['username'] = 'This username already exists!';
            $class['username']  = 'class="form-control is-invalid"';
        }

        if (strcmp($password, $repeatPass) !== 0) {
            $errors['password'] = 'Passwords do not match!';
            $class['password']  = 'class="form-control is-invalid"';
        }

        if (is_numeric($firstName)) {
            $errors['first_name'] = 'First Name should be of type string';
            $class['first_name']  = 'class="form-control is-invalid"';
        }

        if (is_string($firstName) && $firstName !== ''  && strlen($firstName) > 50) {
            $errors['first_name'] = 'First Name should not be more than 50 characters';
            $class['first_name']  = 'class="form-control is-invalid"';
        }

        if (is_string($lastName) && strlen($lastName) > 50) {
            $errors['last_name'] = 'Last Name should not be more than 50 characters';
            $class['last_name']  = 'class="form-control is-invalid"';
        }

        if (is_numeric($lastName)) {
            $errors['last_name'] = 'Last Name should be of type string';
            $class['last_name']  = 'class="form-control is-invalid"';
        }

        if (is_numeric($username)) {
            $errors['username'] = 'Username should be of type string';
            $class['username']  = 'class="form-control is-invalid"';
        }

        return ['class' => $class, 'errors' => $errors];
    }

}
