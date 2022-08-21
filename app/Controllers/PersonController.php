<?php

namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;
use App\System\Registry;
use GuzzleHttp\Client;

class PersonController extends AbstractController
{
    private $client;
    private $url;
    private $segments;

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->url = Registry::get('config')['baseUrl'];
        $this->segments = new CarsController();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function listing()
    {
        $res = $this->client->request('GET', 'https://reqres.in/api/users?page=2');
        $persons =  json_decode($res->getBody(), true);

        $this->renderView('person/listing', ['persons' => $persons, 'url' => $this->url]);
    }

    public function getPerson()
    {
        $id = $this->segments->urlSegments[3];
        $res = $this->client->request('GET', "https://reqres.in/api/users/{$id}");
        $person = json_decode($res->getBody(), true);

        $this->renderView("person/person", ['person' => $person, 'url' => $this->url]);
    }

    public function update()
    {
        if (!$this->isLoggedIn()) {
            $this->setFlashMessage('You need to be logged user');

            $this->redirect('default', 'index');
        }
        $id = $this->segments->urlSegments[3];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $data = $_POST;
            $res = $this->client->request('GET', "https://reqres.in/api/users/{$id}", $data);
            if ($res->getStatusCode() != 200) {
                $this->setFlashMessage('Something went wrong.');
                $this->redirect('person', 'listing');
            }
            $this->setFlashMessage("Person with id {$id} was updated successfully");
            $this->redirect('person', 'listing');
        }

        $res = $this->client->request('GET', "https://reqres.in/api/users/{$id}");
        $person = json_decode($res->getBody(), true);
        $this->renderView("person/update", ['person' => $person, 'url' => $this->url]);
    }

    public function delete()
    {
        if (!$this->isLoggedIn()) {
            $this->setFlashMessage('You need to be logged user');

            $this->redirect('default', 'index');
        }
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'POST') {
            $id = $this->segments->urlSegments[3];
            $res = $this->client->request('DELETE', "https://reqres.in/api/users/{$id}");

            $message = "Record with Id {$id} was not deleted successfully.
                        There are some problem with the delete operation.";
            if ($res->getStatusCode() == 204) {
                $message = "Record with Id {$id} was deleted successfully";
            }
            $this->setFlashMessage($message);
            $this->redirect('person', 'listing');
        }
    }
    /**
     * @return void
     */
    public function index()
    {
        // TODO: Implement index() method.
    }
}