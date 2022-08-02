<?php
namespace App\Controllers;

use App\Model\Collections\CarsCollection;
use App\System\AbstractController;

class CarsController extends AbstractController
{

    public function index()
    {
        $this->renderView('cars/index', []);
    }

    public function create()
    {
        if (!$this->isLoggedIn())
        {
            header("Location: {$this->config['baseUrl']}"); die();
        }
        $this->renderView('cars/create', []);
    }

    public function update()
    {
        var_dump('This is the update method of the CarsController');
    }

    public function delete()
    {
        var_dump('This is the delete method of the CarsController');
    }

    public function listing()
    {
        $limit          = 5;
        $page           = $_GET['page'] ?? 1;
        $offset         = ($page - 1) * $limit;
        $cars           = new \App\Model\Collections\CarsCollection();
        $carsPagination = $cars->getCarsPagination($limit, $offset);
        $carsCount      = $cars->getCarsCount();
        $totalPages          = (int) ceil($carsCount/$limit);

        $this->renderView('cars/listing', ['totalPages' => $totalPages, 'carsPagination' => $carsPagination]);
    }



}