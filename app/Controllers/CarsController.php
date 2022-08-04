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

        $data = [];
        $data['make']               = $_POST['make'] ?? 'Nqma marka';
        $data['model']              = $_POST['model'] ?? 'Nqma model';
        $data['first_registration'] = $_POST['first_registration'] ?? 'Nqma registration';
        $data['transmission']       = $_POST['transmission'] ?? 'Nqma transmission';
        $data['created_at']         = date('Y-m-d H:s:i');

        $cars = new CarsCollection();
        $dataToBeInserted = $cars->insertCar($data);

        $this->renderView('cars/create', ['dataToBeInserted' => $dataToBeInserted]);
    }

    public function update()
    {
        $car = [];
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == 'GET'){
            $carId = $_GET['id'];
            $car = (new CarsCollection())->getCarById($carId);
        } else {
            $car['id']                 = $_POST['id'] ?? 'Nqma id';
            $car['make']               = $_POST['make'] ?? 'Nqma marka';
            $car['model']              = $_POST['model'] ?? 'Nqma model';
            $car['first_registration'] = $_POST['first_registration'] ?? 'Nqma registration';
            $car['transmission']       = $_POST['transmission'] ?? 'Nqma transmission';
            $car['updated_at']         = date('Y-m-d H:s:i');
        }

        $carsCollection = (new CarsCollection())->update(['id' => $car['id']], $car);

        $this->renderView('cars/edit', ['car' => $car]);
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
        $carResults     = $cars->getCarsPagination($limit, $offset);
        $carsCount      = $cars->getCarsCount();
        $totalPages          = (int) ceil($carsCount/$limit);

        $this->renderView('cars/listing', ['totalPages' => $totalPages, 'carResults' => $carResults]);
    }



}