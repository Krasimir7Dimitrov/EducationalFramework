<?php

namespace App\Controllers;

use App\System\AbstractController;
use GuzzleHttp\Client;

class UsersController extends AbstractController
{

    public function index()
    {
        // TODO: Implement index() method.
    }

    public function getAllUsers()
    {
        $client =  new Client();

        try {
            $response = $client->request('get', 'https://reqres.in/api/users?page=1');
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }

        $users = json_decode($response->getBody());

        $this->renderView('/users/list', ['users' => $users->data]);
    }

    public function getUserById()
    {
        $client = new Client();
        $id = $_GET['id'];

        try {
            $response = $client->request('get', 'https://reqres.in/api/users/'. $id);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }

        $users = json_decode($response->getBody());

        $this->renderView('/users/single', ['users' => $users->data]);
    }

    public function create()
    {
        $client = new Client();

        try {
            $response = $client->request('post', 'https://reqres.in/api/users', [
                'json' => [
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                ]
            ]);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }

        $users = json_decode($response->getBody());

        $this->renderView('/users/create', ['users' => $users->data]);
    }

    public function update()
    {
        $client = new Client();
        $id = $_POST['id'];

        try {
            $response = $client->request('put', 'https://reqres.in/api/users/'. $id , [
                'json' => [
                    'id'         => $_POST['id'],
                    'first_name' => $_POST['first_name'],
                    'last_name'  => $_POST['last_name'],
                    'email'      => $_POST['email'],
                    'avatar'     => $_POST['avatar']
                ]
            ]);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }

        $users = json_decode($response->getBody());

        $this->renderView('/users/single', ['users' => $users]);
    }

    public function delete()
    {
        $client = new Client();
        $id = $_GET['id'];

        try {
            $response = $client->request('delete', 'https://reqres.in/api/users/'. $id);
            $usersList = $client->request('get', 'https://reqres.in/api/users?page=1');
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }

        $users = json_decode($usersList->getBody());

        $this->renderView('/users/list', ['users' => $users->data]);
    }

}