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

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
        $this->url = Registry::get('config')['baseUrl'];
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
        $segments = new CarsController();
        $id = $segments->urlSegments[3];
        $res = $this->client->request('GET', "https://reqres.in/api/users/{$id}");
        $person = json_decode($res->getBody(), true);

        $this->renderView("person/person", ['person' => $person, 'url' => $this->url]);
    }

    /**
     * @return void
     */
    public function index()
    {
        // TODO: Implement index() method.
    }
}